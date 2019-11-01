<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Integration;


use Verifone\Core\Executor\FrontendServiceExecutor;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtilImpl;
use Verifone\Core\DependencyInjection\CryptUtils\SeclibCryptography;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Transporter\CurlWrapper;
use Verifone\Core\Service\Frontend\AddNewCardService;
use Verifone\Core\Service\Frontend\CreateNewOrderService;
use Verifone\Core\ServiceFactory;
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrlsImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfigurationImpl;
use Verifone\Core\DependencyInjection\Service\AddressImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\DependencyInjection\Service\ProductImpl;
use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\Tests\Unit\VerifoneTest;

class FrontendIntegrationTest extends VerifoneTest
{
    private $testUrl;
    private $curl;
    private $key;
    private $urls;
    private $customer;
    private $order;
    /**
     * @var FrontendServiceExecutor $exec
     */
    private $exec;

    public function setUp()
    {
        $this->testUrl = 'https://epayment.test.point.fi/pw/payment';
        $this->curl = new CurlWrapper();
        $this->key = file_get_contents('Assets/demo-merchant-agreement-private.pem', true);
        $this->urls = new RedirectUrlsImpl(
            'http://www.testikauppa.fi/success',
            'http://www.testikauppa.fi/rejected',
            'http://www.testikauppa.fi/cancel',
            'http://www.testikauppa.fi/expired',
            'http://www.testikauppa.fi/error'
        );

        $address = new AddressImpl(
            'Street 1',
            '',
            '',
            '00100',
            'Helsinki',
            '123',
            'FirstName',
            'LastName'
        );

        $this->customer = new CustomerImpl(
            'Example',
            'Exemplar',
            '0401234567',
            'example@domain.fi',
            $address
        );

        $this->order = new OrderImpl(
            '1000000260',
            '2016-05-23 11:58:16',
            '978',
            '2590',
            '2089',
            '501',
            '3'
        );

        $container = new ExecutorContainer(array(ExecutorContainer::REQUEST_CONVERTER => ExecutorContainer::REQUEST_CONVERTER_TYPE_HTML));
        $this->exec = $container->getExecutor('frontend');
    }

    public function testCreatingNewOrder()
    {
        $expectedResult = include 'Assets/createNewOrderForm.php';
        $config = new FrontendConfigurationImpl(
            $this->urls,
            $this->key,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            '1'
        );
        /**
         * @var CreateNewOrderService $service
         */
        $service = ServiceFactory::createService($config, 'Frontend\CreateNewOrderService');

        $product1 = new ProductImpl(
            'Parvekeovi Patio',
            '87419',
            '87419',
            '108400',
            '1',
            '0'
        );
        $product2 = new ProductImpl(
            'Toimituskulut - Kotiinkuljetus',
            '7097',
            '7097',
            '8800',
            '1',
            '0'
        );
        $payment = new PaymentInfoImpl('fi_FI', '3');

        $service->insertCustomer($this->customer);
        $service->insertOrder($this->order);
        $service->insertPaymentInfo($payment);
        $service->insertProduct($product1);
        $service->insertProduct($product2);

        $storage = $service->getFields();
        $fields = $storage->getAsArray();
        $expectedResult = $this->getExpectedResult($expectedResult, $fields);
        $form = $this->exec->executeService($service, array('https://epayment.test.point.fi/pw/payment'));
        $this->assertEquals($expectedResult, $form);
    }

    public function testAddingNewCard()
    {
        $expectedResult = include 'Assets/addNewCardForm.php';
        $config = new FrontendConfigurationImpl(
            $this->urls,
            $this->key,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            '1'
        );
        /**
         * @var AddNewCardService $service
         */
        $service = ServiceFactory::createService($config, 'Frontend\AddNewCardService');

        $product = new ProductImpl(
            'Fake product',
            '0',
            '0',
            '0',
            '0',
            '0'
        );

        $payment = new PaymentInfoImpl('fi_FI', '', '');

        $service->insertCustomer($this->customer);
        $service->insertPaymentInfo($payment);
        $service->insertOrder($this->order);
        $service->insertProduct($product);

        $storage = $service->getFields();
        $fields = $storage->getAsArray();
        $expectedResult = $this->getExpectedResult($expectedResult, $fields);
        $form = $this->exec->executeService($service, array('https://epayment.test.point.fi/pw/payment'));
        $this->assertEquals($expectedResult, $form);
    }

    private function generatePaymentToken($fields)
    {
        $merchantAgreementCode = $fields['s-f-1-36_merchant-agreement-code'];
        $orderNumber = $fields['s-f-1-36_order-number'];
        $timestamp = $fields['t-f-14-19_payment-timestamp'];
        return strtoupper(substr(hash('sha256', $merchantAgreementCode . ';' . $orderNumber . ';' . $timestamp), 0, 32));
    }

    private function generateSignature($fields)
    {
        unset($fields['s-t-256-256_signature-two']);
        $cryptUtil = new CryptUtilImpl(new SeclibCryptography());
        return $cryptUtil->generateSignatureTwo($this->key, $fields);
    }

    private function getExpectedResult($expectedResult, $fields)
    {
        $signature = $this->generateSignature($fields);
        $expectedResult = str_replace('placeholder_sig_one', $signature, $expectedResult);

        $token = $this->generatePaymentToken($fields);
        return str_replace('placeholder_for_payment_token', $token, $expectedResult);
    }
}
