<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\CoreResponse\Interfaces;

interface PaymentMethod
{
    public function __construct($code, $type, $minLimit, $maxLimit);

    public function getCode();

    public function getType();
    
    public function getMinLimit();
    
    public function getMaxLimit();
}
