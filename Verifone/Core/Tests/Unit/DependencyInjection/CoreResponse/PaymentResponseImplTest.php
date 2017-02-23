<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\CoreResponse;

use Verifone\Core\DependencyInjection\CoreResponse\PaymentResponseImpl;

class PaymentResponseImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $orderNumber
     * @param $transactionNumber
     * @param $orderGrossAmount
     * @param $paymentMethodCode
     * @param $cancelMessage
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($orderNumber, $transactionNumber, $orderGrossAmount, $paymentMethodCode, $cancelMessage)
    {
        $paymentResponse = new PaymentResponseImpl($orderNumber, $transactionNumber, $orderGrossAmount, $paymentMethodCode, $cancelMessage);
        $this->assertEquals($orderNumber, $paymentResponse->getOrderNumber());
        $this->assertEquals($transactionNumber, $paymentResponse->getTransactionNUmber());
        $this->assertEquals($orderGrossAmount, $paymentResponse->getOrderGrossAmount());
        $this->assertEquals($paymentMethodCode, $paymentResponse->getPaymentMethodCode());
        $this->assertEquals($cancelMessage, $paymentResponse->getCancelMessage());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('d', 'c', 'aa', 'b', 'c'),
            array('', '', '', '', ''),
        );
    }
}
