<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service;

use \TypeError;
use Verifone\Core\Tests\Unit\Service\TestStorage;

abstract class AbstractServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $mockStorage;
    protected $mockConf;
    protected $mockUrls;
    protected $key;
    protected $mockCrypto;
    protected $serviceName;
    protected $mockCustomer;
    protected $mockProduct;
    protected $mockOrder;
    protected $mockPayment;
    protected $mockResponseConverter;
    
    public function setUp()
    {
        $this->mockStorage = new TestStorage();
        $this->mockConf = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration')
            ->getMock();
        $this->mockUrls = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrls')
            ->getMock();

        $this->mockCrypto = $this->getMockBuilder('\Verifone\Core\DependencyInjection\CryptUtils\CryptUtil')->getMock();

        $this->key = file_get_contents('demo-merchant-agreement-private.pem', true);

        $this->mockPayment = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo')
            ->getMock();

        $this->mockResponseConverter = $this->getMockBuilder('\Verifone\Core\Converter\Response\ResponseConverter')->getMock();
    }

    public function testConstructStorageNull()
    {
        $this->expectException(TypeError::class);
        $service = new $this->serviceName(null, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
    }

    public function testConstructConfNull()
    {
        $this->expectException(TypeError::class);
        $service = new $this->serviceName($this->mockStorage, null, $this->mockCrypto, $this->mockResponseConverter);
    }

    public function testInsertCustomerNull()
    {
        $this->expectException(TypeError::class);
        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $service->insertCustomer(null);
    }

    public function testInsertProductNull()
    {
        $this->expectException(TypeError::class);
        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $service->insertProduct(null);
    }

    public function testInsertOrderNull()
    {
        $this->expectException(TypeError::class);
        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);
        $service->insertOrder(null);
    }

    public function testInsertCryptoNull()
    {
        $this->expectException(TypeError::class);
        $service = new $this->serviceName($this->mockStorage, $this->mockConf, null, $this->mockResponseConverter);
    }

    public function getFieldsMultipleTimes()
    {
        $this->mockCrypto->expects($this->once())
            ->method('generateSignatureOne')
            ->will($this->returnValue(''));

        $this->mockConf->expects($this->once())
            ->method('getRedirectUrls')
            ->will($this->returnValue($this->mockUrls));
        $this->mockConf->expects($this->once())
            ->method('getMerchantAgreementCode')
            ->will($this->returnValue('aa'));
        $this->mockConf->expects($this->once())
            ->method('getSoftware')
            ->will($this->returnValue('aa'));
        $this->mockConf->expects($this->once())
            ->method('getSoftwareVersion')
            ->will($this->returnValue('aa'));
        $this->mockConf->expects($this->once())
            ->method('getPrivateKey')
            ->will($this->returnValue($this->key));
        $this->mockConf->expects($this->once())
            ->method('getUrls')
            ->will($this->returnValue(array('asdf')));

        $service = new $this->serviceName($this->mockStorage, $this->mockConf, $this->mockCrypto, $this->mockResponseConverter);

        $resultStorage = $service->getFields();
        $fields = $resultStorage->getAsArray();

        $resultStorage2 = $service->getFields();
        $fields2 = $resultStorage2->getAsArray();

        $this->assertEquals($fields['config_signature_one'], $fields2['config_signature_one']);
    }

    public function testConstructAndGetFields()
    {
        $this->mockCrypto->expects($this->once())
            ->method('generateSignatureOne')
            ->will($this->returnValue(''));

        $this->mockConf->expects($this->once())
            ->method('getMerchantAgreementCode')
            ->will($this->returnValue('aa'));
        $this->mockConf->expects($this->once())
            ->method('getSoftware')
            ->will($this->returnValue('aa'));
        $this->mockConf->expects($this->once())
            ->method('getSoftwareVersion')
            ->will($this->returnValue('aa'));
        $this->mockConf->expects($this->once())
            ->method('getPrivateKey')
            ->will($this->returnValue($this->key));

        $this->mockCustomer = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Customer')
            ->getMock();

        $this->mockProduct = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Product')
            ->getMock();

        $this->mockOrder = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Order')
            ->getMock();
    }
    
    protected function checkKeys($keys)
    {
        $this->assertContains('i-f-1-11_interface-version', $keys);
        $this->assertContains('s-f-1-36_merchant-agreement-code', $keys);
        $this->assertContains('s-f-1-10_software-version', $keys);
        $this->assertContains('s-f-1-30_software', $keys);
        $this->assertContains('s-t-256-256_signature-one', $keys);
    }

    protected function checkCustomer($keys)
    {
        $this->assertContains('s-f-1-30_buyer-first-name', $keys);
        $this->assertContains('s-f-1-30_buyer-last-name', $keys);
        $this->assertContains('s-f-1-100_buyer-email-address', $keys);
        $this->assertContains('s-t-1-30_buyer-phone-number', $keys);
    }
    
    protected function checkProduct($keys, $number)
    {
        $this->assertContains('s-t-1-30_bi-name-' . $number, $keys);
        $this->assertContains('l-t-1-20_bi-unit-cost-' . $number, $keys);
        $this->assertContains('l-t-1-20_bi-gross-amount-' . $number, $keys);
        $this->assertContains('l-t-1-20_bi-net-amount-' . $number, $keys);
        $this->assertContains('i-t-1-11_bi-unit-count-' . $number, $keys);
        $this->assertContains('i-t-1-4_bi-vat-percentage-' . $number, $keys);
        $this->assertContains('i-t-1-4_bi-discount-percentage-' . $number, $keys);
    }
    
    protected function checkPaymentInfo($keys)
    {
        $this->assertContains('locale-f-2-5_payment-locale', $keys);
        $this->assertContains('t-f-14-19_payment-timestamp', $keys);
    }
    
    protected function checkOrder($keys)
    {
        $this->assertContains('s-f-1-36_order-number', $keys);
        $this->assertContains('t-f-14-19_order-timestamp', $keys);
        $this->assertContains('i-f-1-3_order-currency-code', $keys);
        $this->assertContains('l-f-1-20_order-gross-amount', $keys);
    }
}
