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


interface PaymentResponse
{
    public function __construct(
        $orderNumber,
        $transactionNumber,
        $orderGrossAmount,
        $paymentMethodCode,
        $cancelMessage,
        Card $card = null
    );

    public function getOrderNumber();

    public function getTransactionNumber();

    public function getOrderGrossAmount();

    public function getPaymentMethodCode();
    
    public function getCancelMessage();
    
    public function getCard();
}
