<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\CoreResponse;

use Verifone\Core\DependencyInjection\CoreResponse\Interfaces\PaymentMethod;

class PaymentMethodImpl implements PaymentMethod
{
    private $code;
    private $type;

    public function __construct($code, $type)
    {
        $this->type = $type;
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getType()
    {
        return $this->type;
    }
}
