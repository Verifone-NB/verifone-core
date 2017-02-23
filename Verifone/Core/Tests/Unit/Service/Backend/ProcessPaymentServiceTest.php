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

class ProcessPaymentServiceTest extends AbstractBackendServiceTest
{
    public function setUp()
    {
        parent::setUp();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration')
            ->getMock();
        $this->serviceName = '\Verifone\Core\Service\Backend\ProcessPaymentService';
    }

    public function testConstructAndGetFields()
    {
        parent::testConstructAndGetFields();

        $this->mockCustomer->expects($this->once())
            ->method('getFirstName')
            ->will($this->returnValue(''));
        $this->mockCustomer->expects($this->once())
            ->method('getLastName')
            ->will($this->returnValue(''));
        $this->mockCustomer->expects($this->once())
            ->method('getPhoneNumber')
            ->will($this->returnValue(''));
        $this->mockCustomer->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue(''));

        $this->mockOrder = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Order')
            ->getMock();
        $this->mockOrder->expects($this->once())
            ->method('getIdentificator')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->once())
            ->method('getTimestamp')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->once())
            ->method('getTotalInclTax')
            ->will($this->returnValue(''));

        $this->mockProduct = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Product')
            ->getMock();
        $this->mockProduct->expects($this->exactly(2))
            ->method('getName')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(2))
            ->method('getUnitPriceExclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(4))
            ->method('getPriceExclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(4))
            ->method('getPriceInclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(2))
            ->method('getQuantity')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(2))
            ->method('getDiscountPercentage')
            ->will($this->returnValue(''));

        $mockPayment = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo')
            ->getMock();
        $mockPayment->expects($this->once())->method('getLocale');
        $mockPayment->expects($this->once())->method('getSavedMethodId');
        $mockPayment->expects($this->once())->method('getRecurring');

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertPaymentInfo($mockPayment);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->checkCustomer($keys);
        $this->checkProduct($keys, 0);
        $this->checkProduct($keys, 1);
        $this->checkPaymentInfo($keys);
        $this->checkOrder($keys);
        
        $this->assertEquals('process-payment', $fields['s-f-1-30_operation']);
        $this->assertContains('l-t-1-20_saved-payment-method-id', $keys);

        $this->assertEquals(33, count($keys));
    }
}
