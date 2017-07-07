<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Storage;

use Verifone\Core\Exception\StorageValueOverwriteException;
use Verifone\Core\Exception\StorageKeyNotInKeyspaceException;

/**
 * Class ArrayStorage
 * @package Verifone\Core\Storage
 * Implements storage with Array and validates keys against possible key space
 */
final class ArrayStorage implements Storage
{
    const CONF_COUNTABLE = 'countable';
    private $storage;
    private $possibleKeySpace;

    /**
     * ArrayStorage constructor.
     * @param $possibleKeySpace array map of possible keys that can be stored and their conf
     */
    public function __construct($possibleKeySpace)
    {
        $this->storage = array();
        $this->possibleKeySpace = $possibleKeySpace;
    }

    /**
     * If key is in possible key space and not already saved as a key-value pair,
     * a key -value pair is stored into the storage.
     * @param string $key to be added
     * @param string $value to be mapped to key
     * @return $this
     * @throws StorageKeyNotInKeyspaceException if key is not in keys pace
     * @throws StorageValueOverwriteException if key is already saved into storage
     */
    public function add($key, $value)
    {
        $constraintKey = $this->validateKey($key, $value);

        // is there anything stored in storage with real key?
        if (isset($this->storage[$key])) {
            throw new StorageValueOverwriteException($key, $value, $this->storage[$key]);
        }

        // store value with real key
        $constraints = $this->possibleKeySpace[$constraintKey];
        $this->storage[$key] = $this->formValue($constraints, $value);
        return $this;
    }

    private function formValue($constraints, $value)
    {
        if ($this->shouldBeCut($constraints)) {
            return mb_substr($value, 0, $constraints['max']);
        }
        return $value;
    }

    private function shouldBeCut($constraints)
    {
        return isset($constraints['cut']) && $constraints['cut'] === true
            && isset($constraints['max']) && is_int($constraints['max']);
    }

    /**
     * Get value corresponding to given key
     * @param string $key to get a value
     * @return bool|string value if found, otherwise false
     */
    public function get($key)
    {
        if (isset($this->storage[$key])) {
            return $this->storage[$key];
        }
        return false;
    }

    /**
     * @return array of all real key - value pairs
     */
    public function getAsArray()
    {
        return $this->storage;
    }

    public function isValidUncountableKey($key)
    {
        return $this->isCorrectlyDefined($key) && (!isset($this->possibleKeySpace[$key][self::CONF_COUNTABLE])
            || $this->possibleKeySpace[$key][self::CONF_COUNTABLE] === false);
    }

    /**
     * Whether the key is a valid countable key with a number as suffix, that is, of mode example-key-1
     * @param string $key
     * @return bool true if is valid countable key, false if not
     */
    private function isValidCountableKey($key)
    {
        $number = $this->getNumberFromCountableKey($key);
        if (!ctype_digit($number)) {
            return false;
        }
        $key = $this->getCountableKeyWithoutNumber($key);
        return $this->isCorrectlyDefined($key) && isset($this->possibleKeySpace[$key][self::CONF_COUNTABLE])
            && $this->possibleKeySpace[$key][self::CONF_COUNTABLE];
    }

    /**
     * Whether the key has a valid configuration in possible keyspace
     * @param string $key
     * @return bool
     */
    private function isCorrectlyDefined($key) {
        return isset($this->possibleKeySpace[$key]) && is_array($this->possibleKeySpace[$key]);
    }

    /**
     * Validate that the key exists in the possible keyspace
     * @param string $key to validate
     * @param string $value only needed for exception information
     * @return string key used in configuration
     * @throws StorageKeyNotInKeyspaceException if key was not in possible keyspace
     */
    private function validateKey($key, $value)
    {
        // if key is found as itself, return true
        if ($this->isValidUncountableKey($key)) {
            return $key;
        }

        // if not, check if it is a valid countable key
        if (strpos($key, '-') !== false && $this->isValidCountableKey($key)) {
            return $this->getCountableKeyWithoutNumber($key);
        }

        throw new StorageKeyNotInKeyspaceException($key, $value);
    }

    /**
     * Get the number at the end of the key.
     * @param string $key
     * @return string
     */
    private function getNumberFromCountableKey($key)
    {
        return substr($key, strrpos($key, '-') + 1);
    }

    /**
     * Get the key of type key-name-x-1 in mode key-name-x-. That is, get everything else than the last number.
     * @param string $key
     * @return string
     */
    private function getCountableKeyWithoutNumber($key)
    {
        return substr($key, 0, strrpos($key, '-') + 1);
    }
}
