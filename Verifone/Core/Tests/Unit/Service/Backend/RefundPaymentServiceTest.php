<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service\Backend;


class RefundPaymentServiceTest extends AbstractBackendServiceTest
{
    public function setUp()
    {
        parent::setUp();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration')
            ->getMock();
        $this->serviceName = '\Verifone\Core\Service\Backend\RefundPaymentService';
    }


    public function testConstructAndGetFields()
    {
        parent::testConstructAndGetFields();
        $this->mockCustomer->expects($this->never())
            ->method('getFirstName')
            ->will($this->returnValue(''));
        $this->mockCustomer->expects($this->never())
            ->method('getLastName')
            ->will($this->returnValue(''));
        $this->mockCustomer->expects($this->never())
            ->method('getPhoneNumber')
            ->will($this->returnValue(''));
        $this->mockCustomer->expects($this->never())
            ->method('getEmail')
            ->will($this->returnValue(''));

        $this->mockOrder->expects($this->never())
            ->method('getIdentificator')
            ->will($this->returnValue(''));

        $this->mockProduct = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Product')
            ->getMock();
        $this->mockProduct->expects($this->exactly(0))
            ->method('getName')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(0))
            ->method('getUnitPriceExclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(0))
            ->method('getPriceExclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(0))
            ->method('getPriceInclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(0))
            ->method('getQuantity')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(0))
            ->method('getDiscountPercentage')
            ->will($this->returnValue(''));

        $transaction = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Transaction')
            ->getMock();
        $transaction->expects($this->once())->method('getNumber');
        $transaction->expects($this->once())->method('getMethodCode');
        $transaction->expects($this->exactly(4))->method('getRefundAmount');
        $transaction->expects($this->once())->method('getRefundCurrency');

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertRefundProduct($transaction);
        $service->insertTransaction($transaction);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->assertEquals('refund-payment', $fields['s-f-1-30_operation']);
        $this->assertContains('s-f-1-30_payment-method-code', $keys);
        $this->assertContains('l-f-1-20_transaction-number', $keys);
        $this->assertContains('i-f-1-3_refund-currency-code', $keys);
        $this->assertContains('l-f-1-20_refund-amount', $keys);
        $this->assertContains('s-t-1-36_order-note', $keys);
        $this->assertEquals(20, count($keys));
    }
}
