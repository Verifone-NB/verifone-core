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
 * Class GetPaymentStatusServiceTest
 * @package Verifone\Core\Tests\Service\Backend
 * @codeCoverageIgnore
 */
class GetPaymentStatusServiceTest extends AbstractBackendServiceTest
{
    public function setUp()
    {
        parent::setUp();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration')
            ->getMock();
        $this->serviceName = '\Verifone\Core\Service\Backend\GetPaymentStatusService';
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


        $transaction = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Transaction')
            ->getMock();
        $transaction->expects($this->once())->method('getNumber');
        $transaction->expects($this->once())->method('getMethodCode');
        $transaction->expects($this->never())->method('getRefundAmount');
        $transaction->expects($this->never())->method('getRefundCurrency');

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertTransaction($transaction);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->assertEquals('get-payment-status', $fields['s-f-1-30_operation']);
        $this->assertContains('s-f-1-30_payment-method-code', $keys);
        $this->assertContains('l-f-1-20_transaction-number', $keys);
        $this->assertEquals(10, count($keys));
    }
}
