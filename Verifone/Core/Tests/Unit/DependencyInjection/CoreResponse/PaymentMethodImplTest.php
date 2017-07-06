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


use Verifone\Core\DependencyInjection\CoreResponse\PaymentMethodImpl;
use Verifone\Core\Tests\Unit\VerifoneTest;

class PaymentMethodImplTest extends VerifoneTest
{
    /**
     * @param $type
     * @param $code
     * @param $max
     * @param $min
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($type, $code, $min, $max)
    {
        $paymentMethod = new PaymentMethodImpl($code, $type, $min, $max);
        $this->assertEquals($code, $paymentMethod->getCode());
        $this->assertEquals($type, $paymentMethod->getType());
        $this->assertEquals($min, $paymentMethod->getMinLimit());
        $this->assertEquals($max, $paymentMethod->getMaxLimit());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'b', 'd', 'c'),
            array('', '', '', ''),
        );
    }
}
