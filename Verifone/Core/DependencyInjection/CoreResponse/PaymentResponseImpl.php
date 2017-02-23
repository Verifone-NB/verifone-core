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


use Verifone\Core\DependencyInjection\CoreResponse\Interfaces\PaymentResponse;

class PaymentResponseImpl implements PaymentResponse
{
    private $orderNumber;
    private $transactionNumber;
    private $orderGrossAmount;
    private $paymentMethodCode;
    private $cancelMessage;
    
    public function __construct($orderNumber, $transactionNumber, $orderGrossAmount, $paymentMethodCode, $cancelMessage)
    {
        $this->orderNumber = $orderNumber;
        $this->transactionNumber = $transactionNumber;
        $this->orderGrossAmount = $orderGrossAmount;
        $this->paymentMethodCode = $paymentMethodCode;
        $this->cancelMessage = $cancelMessage;
    }
    
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }
    
    public function getCancelMessage()
    {
        return $this->cancelMessage;
    }
    
    public function getOrderGrossAmount()
    {
        return $this->orderGrossAmount;
    }
    
    public function getPaymentMethodCode()
    {
        return $this->paymentMethodCode;
    }
    
    public function getTransactionNUmber()
    {
        return $this->transactionNumber;
    }
}
