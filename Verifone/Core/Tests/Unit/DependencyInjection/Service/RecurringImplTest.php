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

class RecurringImplTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $subscriptionName
     * @param $subscriptionCode
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($subscriptionName, $subscriptionCode) {
        $recurring = new RecurringImpl($subscriptionName, $subscriptionCode);
        $this->assertEquals($subscriptionName, $recurring->getSubscriptionName());
        $this->assertEquals($subscriptionCode, $recurring->getSubscriptionCode());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'a'),
            array('', ''),
        );
    }
}
