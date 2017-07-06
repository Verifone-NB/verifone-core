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
use Verifone\Core\Tests\Unit\VerifoneTest;

class PaymentInfoImplTest extends VerifoneTest
{

    /**
     * @param $locale
     * @param $saveMethod
     * @param $savedMethodId
     * @param $saveMaskedPan
     * @param $recurring
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($locale, $saveMethod, $savedMethodId, $note, $saveMaskedPan, $recurring)
    {
        $paymentInfo = new PaymentInfoImpl($locale, $saveMethod, $savedMethodId, $note, $saveMaskedPan, $recurring);
        $this->assertEquals($locale, $paymentInfo->getLocale());
        $this->assertEquals($saveMethod, $paymentInfo->getSaveMethod());
        $this->assertEquals($savedMethodId, $paymentInfo->getSavedMethodId());
        $this->assertEquals($recurring, $paymentInfo->getRecurring());
        $this->assertEquals($saveMaskedPan, $paymentInfo->getSaveMaskedPan());
        $this->assertEquals($note, $paymentInfo->getNote());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'a', 'a', 'a', false, null),
            array('', '', '', '', true, new RecurringImpl('a', 'a')),
        );
    }
}
