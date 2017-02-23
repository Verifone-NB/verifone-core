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

class PaymentMethodImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $type
     * @param $code
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($type, $code)
    {
        $paymentMethod = new PaymentMethodImpl($code, $type);
        $this->assertEquals($code, $paymentMethod->getCode());
        $this->assertEquals($type, $paymentMethod->getType());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('d', 'c'),
            array('', ''),
        );
    }
}
