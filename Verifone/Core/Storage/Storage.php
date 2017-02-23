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

/**
 * Interface StorageInterface
 * @package Verifone\Core\Storage
 * Stores values mapped to keys
 */
interface Storage
{
    /**
     * @param $key string to be added
     * @param $value string be mapped to key
     * Adds a key-value pair to the storage
     */
    public function add($key, $value);

    /**
     * @param $key string to be queried
     * @return string value mapped to the given key, if found
     */
    public function get($key);

    /**
     * @return array key-value pairs
     */
    public function getAsArray();
}
