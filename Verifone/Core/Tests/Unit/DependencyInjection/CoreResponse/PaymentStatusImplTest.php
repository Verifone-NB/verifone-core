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


use Verifone\Core\DependencyInjection\CoreResponse\PaymentStatusImpl;
use Verifone\Core\Tests\Unit\VerifoneTest;

class PaymentStatusImplTest extends VerifoneTest
{
    /**
     * @param $code
     * @param $orderAmount
     * @param $transactionNumber
     * @param $paymentMethodCode
     * @param $orderNumber
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($code, $orderAmount, $transactionNumber, $paymentMethodCode, $orderNumber)
    {
        $paymentStatus = new PaymentStatusImpl($code, $orderAmount, $transactionNumber, $paymentMethodCode, $orderNumber);
        $this->assertEquals($code, $paymentStatus->getCode());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('d', 'd', 'd', 'd', 'd'),
            array('', '', '', '', ''),
        );
    }
}
