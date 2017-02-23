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

use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\Exception\FieldValidationFailedException;

/**
 * Class OrderImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Service
 */
class OrderImplTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $identificator
     * @param $timestamp
     * @param $currency
     * @param $totalInclTax
     * @param $totalExclTax
     * @param $taxAmount
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($identificator, $timestamp, $currency, $totalInclTax, $totalExclTax, $taxAmount)
    {
        $order = new OrderImpl($identificator, $timestamp, $currency, $totalInclTax, $totalExclTax, $taxAmount);
        $this->assertEquals($identificator, $order->getIdentificator());
        $this->assertEquals($timestamp, $order->getTimestamp());
        $this->assertEquals($currency, $order->getCurrency());
        $this->assertEquals($totalInclTax, $order->getTotalInclTax());
        $this->assertEquals($totalExclTax, $order->getTotalExclTax());
        $this->assertEquals($taxAmount, $order->getTaxAmount());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('123455asdf', '2016-01-01 01:01:44', '123', '1', '2', '3'),
            array('a', '1900-12-31 02:09:22', '1', '0', '1', '0'),
        );
    }
}
