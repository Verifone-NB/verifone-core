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

/**
 * Class RemoveSavedCreditCardsServiceTest
 * @package Verifone\Core\Tests\Service\Backend
 * @codeCoverageIgnore
 */
class RemoveSavedCreditCardsServiceTest extends AbstractBackendServiceTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration')
            ->getMock();
        $this->serviceName = '\Verifone\Core\Service\Backend\RemoveSavedCreditCardsService';
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

        $this->mockPayment->expects($this->never())->method('getLocale');
        $this->mockPayment->expects($this->once())->method('getSavedMethodId');
        $this->mockPayment->expects($this->never())->method('getRecurring');

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertPaymentInfo($this->mockPayment);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->assertEquals('remove-saved-payment-method', $fields['s-f-1-30_operation']);
        $this->assertContains('l-t-1-20_saved-payment-method-id', $keys);
        $this->assertEquals(9, count($keys));
    }
}
