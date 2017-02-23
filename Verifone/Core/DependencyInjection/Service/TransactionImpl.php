<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Service;


use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;

class TransactionImpl implements Transaction
{
    private $number;
    private $methodCode;
    private $refundAmount;
    private $refundCurrency;

    public function __construct($methodCode, $number, $refundAmount = '', $refundCurrency = '')
    {
        $this->methodCode = $methodCode;
        $this->number = $number;
        $this->refundAmount = $refundAmount;
        $this->refundCurrency = $refundCurrency;
    }

    public function getMethodCode()
    {
        return $this->methodCode;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getRefundAmount()
    {
        return $this->refundAmount;
    }

    public function getRefundCurrency()
    {
        return $this->refundCurrency;
    }
}
