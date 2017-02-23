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
 * Class StorageValueOverwriteException
 * @package Verifone\Core\Exception
 * thrown when trying to add a value to storage with key that already exists in storage
 */
class StorageValueOverwriteException extends Exception
{
    /**
     * StorageValueOverwriteException constructor.
     * @param string $key that is tried to save
     * @param string $newValue that is tried to save for key
     * @param string $oldValue already saved for given key
     * @param int $code
     * @param Exception|null $previous
     * 
     */
    public function __construct($key, $newValue, $oldValue, $code = 0, Exception $previous = null)
    {
        $message = "Trying to store a value " . $newValue . " with key "
        . $key . " but key already exists with value " . $oldValue;

        parent::__construct($message, $code, $previous);
    }
}
