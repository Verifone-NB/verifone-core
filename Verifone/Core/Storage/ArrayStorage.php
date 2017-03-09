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
        $this->validateKey($key, $value);

        // is there anything stored in storage with real key?
        if (isset($this->storage[$key])) {
            throw new StorageValueOverwriteException($key, $value, $this->storage[$key]);
        }

        // store value with real key
        $this->storage[$key] = $value;
        return $this;
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

    private function isCorrectlyDefined($key) {
        return isset($this->possibleKeySpace[$key]) && is_array($this->possibleKeySpace[$key]);
    }

    private function validateKey($key, $value)
    {
        // if key is found as itself, return true
        if ($this->isValidUncountableKey($key)) {
            return;
        }

        // if not, check if it is a valid countable key
        if (strpos($key, '-') !== false && $this->isValidCountableKey($key)) {
            return;
        }

        throw new StorageKeyNotInKeyspaceException($key, $value);
    }

    private function getNumberFromCountableKey($key)
    {
        return substr($key, strrpos($key, '-') + 1);
    }

    private function getCountableKeyWithoutNumber($key)
    {
        return substr($key, 0, strrpos($key, '-') + 1);
    }
}