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
 * Class CurlFailedException
 * @package Verifone\Core\Exception
 * Thrown when curl fails for some reason
 */
class CryptUtilException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 0, null);
    }
}
