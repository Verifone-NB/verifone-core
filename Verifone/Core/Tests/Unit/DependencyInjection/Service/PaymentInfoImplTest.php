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

class PaymentInfoImplTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $locale
     * @param $saveMethod
     * @param $savedMethodId
     * @param $recurring
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($locale, $saveMethod, $savedMethodId, $recurring)
    {
        $paymentInfo = new PaymentInfoImpl($locale, $saveMethod, $savedMethodId, $recurring);
        $this->assertEquals($locale, $paymentInfo->getLocale());
        $this->assertEquals($saveMethod, $paymentInfo->getSaveMethod());
        $this->assertEquals($savedMethodId, $paymentInfo->getSavedMethodId());
        $this->assertEquals($recurring, $paymentInfo->getRecurring());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'a', 'a', null, null),
            array('', '', '', new RecurringImpl('a', 'a')),
        );
    }
}
