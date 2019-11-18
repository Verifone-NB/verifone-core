<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service\Frontend;

/**
 * Class AddNewCardServiceTest
 * @package Verifone\Core\Tests\Service\Frontend
 * @codeCoverageIgnore 
 */
class AddNewCardServiceTest extends AbstractFrontendServiceTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->serviceName = '\Verifone\Core\Service\Frontend\AddNewCardService';
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

        $this->mockProduct->expects($this->never())
            ->method('getName')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->never())
            ->method('getUnitPriceExclTax')
            ->will($this->returnValue('0'));
        $this->mockProduct->expects($this->never())
            ->method('getPriceExclTax')
            ->will($this->returnValue('0'));
        $this->mockProduct->expects($this->never())
            ->method('getPriceInclTax')
            ->will($this->returnValue('0'));
        $this->mockProduct->expects($this->never())
            ->method('getQuantity')
            ->will($this->returnValue('0'));
        $this->mockProduct->expects($this->never())
            ->method('getDiscountPercentage')
            ->will($this->returnValue('0'));

        $this->mockOrder->expects($this->never())
            ->method('getIdentificator')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTimestamp')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTotalInclTax')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTotalExclTax')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTaxAmount')
            ->will($this->returnValue(''));

        $this->mockPayment->expects($this->once())->method('getLocale');
        $this->mockPayment->expects($this->never())->method('getSavedMethodId');
        $this->mockPayment->expects($this->never())->method('getRecurring');

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertProduct($this->mockProduct);
        $service->insertPaymentInfo($this->mockPayment);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->checkProduct($keys, 0);
        $this->checkPaymentInfo($keys);
        $this->checkCustomer($keys);
        $this->checkOrder($keys);
        $this->assertCount(34, $keys);
    }
}

