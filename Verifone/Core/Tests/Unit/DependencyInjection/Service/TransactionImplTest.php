<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Service;


use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Service\RecurringImpl;
use Verifone\Core\DependencyInjection\Service\TransactionImpl;

class TransactionImplTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $methodCode
     * @param $number
     * @param $refundAmount
     * @param $refundCurrency
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($methodCode, $number, $refundAmount, $refundCurrency)
    {
        $transaction = new TransactionImpl($methodCode, $number, $refundAmount, $refundCurrency);
        $this->assertEquals($methodCode, $transaction->getMethodCode());
        $this->assertEquals($number, $transaction->getNumber());
        $this->assertEquals($refundAmount, $transaction->getRefundAmount());
        $this->assertEquals($refundCurrency, $transaction->getRefundCurrency());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'a', 'a', 'aa'),
            array('', '', '', ''),
        );
    }
}
