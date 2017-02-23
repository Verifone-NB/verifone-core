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

class ListTransactionNumbersServiceTest extends AbstractBackendServiceTest
{
    public function setUp()
    {
        parent::setUp();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration')
            ->getMock();
        $this->serviceName = '\Verifone\Core\Service\Backend\ListTransactionNumbersService';
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

        $this->mockOrder->expects($this->once())
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
        $this->assertEquals('list-transaction-numbers', $fields['s-f-1-30_operation']);
        $this->assertContains('s-f-1-36_order-number', $keys);
        $this->assertEquals(9, count($keys));
    }
}
