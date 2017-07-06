<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Configuration\Backend;


use Verifone\Core\DependencyInjection\Configuration\Backend\GetAvailablePaymentMethodsConfigurationImpl;
use Verifone\Core\Exception\FieldValidationFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class GetAvailablePaymentMethodsConfigurationImplTest
 * @package Verifone\Core\Tests\Unit\DependecyInjection\Configuration\Backend
 * @codeCoverageIgnore 
 */
class GetAvailablePaymentMethodsConfigurationImplTest extends VerifoneTest
{
    /**
     * @param $currency
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($currency)
    {
        $conf = new GetAvailablePaymentMethodsConfigurationImpl('a', 'a', 'a', '0', array('z'), $currency);
        $this->assertEquals($conf->getCurrency(), $currency);
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('12'), // common case
        );
    }
}
