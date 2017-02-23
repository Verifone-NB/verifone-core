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
class TransportationFailedException extends Exception
{
    public function __construct($url, $data)
    {
        if (is_array($data) || is_object($data)) {
            $message = 'Trying to trasport data to url ' . $url . ' failed';
        } else {
            $message = 'Trying to transport to ' . $url . ' with data ' . $data . ' failed.';

        }
        parent::__construct($message, 0, null);
    }
}
