<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Service\Interfaces;


interface Transaction
{
    public function __construct($methodCode, $number, $refundAmount, $refundCurrency);
    
    public function getNumber();
    
    public function getMethodCode();
    
    public function getRefundAmount();
    
    public function getRefundCurrency();
}
