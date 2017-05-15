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
 * Class CreateNewOrderServiceTest
 * @package Verifone\Core\Tests\Service\Frontend
 * @codeCoverageIgnore
 */
class CreateNewOrderServiceTest extends AbstractFrontendServiceTest
{
    private $transaction;

    public function setUp()
    {
        parent::setUp();
        $this->serviceName = '\Verifone\Core\Service\Frontend\CreateNewOrderService';
    }

    public function testConstructAndGetFields()
    {
        parent::testConstructAndGetFields();

        $mockAddress = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Address')
            ->getMock();

        $this->mockCustomer->expects($this->exactly(2))
            ->method('getAddress')
            ->will($this->returnValue($mockAddress));

        $this->doMockExpectsForConstructAndGetFields();

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertPaymentInfo($this->mockPayment);
        $service->insertOrder($this->mockOrder);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertTransaction($this->transaction);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->checkCustomer($keys);
        $this->checkProduct($keys, 0);
        $this->checkProduct($keys, 1);
        $this->checkProduct($keys, 2);
        $this->checkPaymentInfo($keys);
        $this->checkOrder($keys);
        $this->assertContains('s-t-1-30_delivery-address-line-one', $keys);
        $this->assertContains('s-t-1-30_delivery-address-line-two', $keys);
        $this->assertContains('s-t-1-30_delivery-address-line-three', $keys);
        $this->assertContains('s-t-1-30_delivery-address-city', $keys);
        $this->assertContains('s-t-1-30_delivery-address-postal-code', $keys);
        $this->assertContains('i-t-1-3_delivery-address-country-code', $keys);
        $this->assertContains('s-t-1-30_payment-method-code', $keys);
        $this->assertContains('l-t-1-20_saved-payment-method-id', $keys);
        $this->assertEquals(55, count($keys));
    }

    public function testConstructAndGetFieldsNullAddress()
    {
        parent::testConstructAndGetFields();

        $this->mockCustomer->expects($this->once())
            ->method('getAddress')
            ->will($this->returnValue(null));

        $this->doMockExpectsForConstructAndGetFields();

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto);
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertCustomer($this->mockCustomer);
        $service->insertOrder($this->mockOrder);
        $service->insertPaymentInfo($this->mockPayment);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertProduct($this->mockProduct);
        $service->insertTransaction($this->transaction);

        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkKeys($keys);
        $this->checkCustomer($keys);
        $this->checkProduct($keys, 0);
        $this->checkProduct($keys, 1);
        $this->checkProduct($keys, 2);
        $this->checkPaymentInfo($keys);
        $this->checkOrder($keys);
        $this->assertContains('s-t-1-30_payment-method-code', $keys);
        $this->assertContains('l-t-1-20_saved-payment-method-id', $keys);
        $this->assertEquals($fields['i-t-1-4_bi-vat-percentage-0'], 2300);
        $this->assertEquals(49, count($keys));
    }

    private function doMockExpectsForConstructAndGetFields()
    {
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

        $this->mockOrder->expects($this->exactly(2))
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
        $this->mockOrder->expects($this->once())
            ->method('getTotalExclTax')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->once())
            ->method('getTaxAmount')
            ->will($this->returnValue(''));


        $this->mockProduct->expects($this->exactly(3))
            ->method('getName')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->exactly(3))
            ->method('getUnitPriceExclTax')
            ->will($this->returnValue('1230'));
        $this->mockProduct->expects($this->exactly(6))
            ->method('getPriceExclTax')
            ->will($this->returnValue('1000'));
        $this->mockProduct->expects($this->exactly(6))
            ->method('getPriceInclTax')
            ->will($this->returnValue('1230'));
        $this->mockProduct->expects($this->exactly(3))
            ->method('getQuantity')
            ->will($this->returnValue('1'));
        $this->mockProduct->expects($this->exactly(3))
            ->method('getDiscountPercentage')
            ->will($this->returnValue(''));

        $this->transaction = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Transaction')
            ->getMock();
        $this->transaction->expects($this->never())->method('getNumber');
        $this->transaction->expects($this->once())->method('getMethodCode');
        $this->transaction->expects($this->never())->method('getRefundAmount');
        $this->transaction->expects($this->never())->method('getRefundCurrency');

        $this->mockPayment->expects($this->once())->method('getLocale');
        $this->mockPayment->expects($this->once())->method('getSavedMethodId');
        $this->mockPayment->expects($this->once())->method('getRecurring');
    }


    /**
     * @param $value
     * @dataProvider providerTestProductTaxCalculation
     */
    public function testProductTaxCalculation($value)
    {
        $this->mockConf->expects($this->once())
            ->method('getRedirectUrls')
            ->will($this->returnValue($this->mockUrls));

        $mockProduct = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Product')
            ->getMock();
        $mockProduct->expects($this->exactly(1))
            ->method('getName')
            ->will($this->returnValue(''));
        $mockProduct->expects($this->exactly(1))
            ->method('getUnitPriceExclTax')
            ->will($this->returnValue($value));
        $mockProduct->expects($this->exactly(2))
            ->method('getPriceExclTax')
            ->will($this->returnValue($value));
        $mockProduct->expects($this->exactly(2))
            ->method('getPriceInclTax')
            ->will($this->returnValue($value));
        $mockProduct->expects($this->exactly(1))
            ->method('getQuantity')
            ->will($this->returnValue($value));
        $mockProduct->expects($this->exactly(1))
            ->method('getDiscountPercentage')
            ->will($this->returnValue($value));

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto);
        $service->insertProduct($mockProduct);

    }

    public function providerTestProductTaxCalculation()
    {
        return array(
            array(''),
            array('0'),
            array(0),
            array('a'),
            array(true),
            array(false)
        );
    }
}
