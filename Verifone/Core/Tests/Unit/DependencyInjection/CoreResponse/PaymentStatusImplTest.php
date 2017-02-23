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

class PaymentStatusImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $code
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($code)
    {
        $paymentStatus = new PaymentStatusImpl($code);
        $this->assertEquals($code, $paymentStatus->getCode());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('d'),
            array(''),
        );
    }
}
