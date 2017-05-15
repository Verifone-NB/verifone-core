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
    private $minLimit;
    private $maxLimit;

    public function __construct($code, $type, $minLimit, $maxLimit)
    {
        $this->type = $type;
        $this->code = $code;
        $this->minLimit = $minLimit;
        $this->maxLimit = $maxLimit;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMaxLimit()
    {
        return $this->maxLimit;
    }

    public function getMinLimit()
    {
        return $this->minLimit;
    }
}
