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

class UnableToConvertFieldsException extends Exception
{

    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        if (!isset($message) || trim($message) === '') {
            $message = 'Was unable to convert fields to desired format';
        }
        parent::__construct($message, $code, $previous);
    }
}
