<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service;

use Verifone\Core\Storage\Storage;
/**
 * Class TestStorage
 * @package Verifone\Core\Tests\Service\Frontend
 * A fake storage class to test what the services are trying to insert into storage
 * @codeCoverageIgnore
 */
class TestStorage implements Storage
{
    private $storage;

    public function __construct()
    {
        $this->storage = array();
    }

    public function add($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function get($key)
    {
        return false;
    }

    public function getAsArray()
    {
        return $this->storage;
    }
}
