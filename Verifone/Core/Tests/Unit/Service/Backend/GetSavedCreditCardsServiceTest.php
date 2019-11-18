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
 * Class GetSavedCreditCardsServiceTest
 * @package Verifone\Core\Tests\Service\Backend
 * @codeCoverageIgnore
 */
class GetSavedCreditCardsServiceTest extends AbstractBackendServiceTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration')
            ->getMock();
        $this->serviceName = '\Verifone\Core\Service\Backend\GetSavedCreditCardsService';
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

        $this->mockOrder->expects($this->never())
            ->method('getIdentificator')
            ->will($this->returnValue(''));

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->checkCustomer($keys);
        $this->assertEquals('list-saved-payment-methods', $fields['s-f-1-30_operation']);
        $this->assertEquals(12, count($keys));
    }
}
