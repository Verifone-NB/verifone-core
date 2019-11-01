<?php

/**
 *  NOTICE OF LICENSE
 *
 *  This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2019 Lamia Oy (https://lamia.fi)
 */

namespace Verifone\Core\Exception;

use \Exception;

/**
 * Class MissingBillingAddressException
 * @package Verifone\Core\Exception
 * Thrown when billing address is not available
 */
class MissingBillingAddressException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 0, null);
    }
}