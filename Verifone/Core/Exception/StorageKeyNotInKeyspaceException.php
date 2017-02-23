<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Exception;

use \Exception;

/**
 * Class StorageKeyNotInKeyspaceException
 * @package Verifone\Core\Exception
 * thrown when given key for storage is not in valid storage key space
 */
class StorageKeyNotInKeyspaceException extends Exception
{

    /**
     * StorageKeyNotInKeyspaceException constructor.
     * @param string $key that is tried to use, inserted into error message
     * @param int $value that is tried to save with the key, inserted into error message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($key, $value, $code = 0, Exception $previous = null)
    {
        $message = "Trying to store a value " . $value . " with key "
        . $key . " but key is not in available keyspace";

        parent::__construct($message, $code, $previous);
    }
}
