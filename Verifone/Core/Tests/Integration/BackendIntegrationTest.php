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


use Verifone\Core\Exception\ResponseCheckFailedException;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Service\TransactionImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\GetAvailablePaymentMethodsConfigurationImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\ServiceFactory;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class BackendIntegrationTest
 * @package Verifone\Core\Tests\Integration
 */
class BackendIntegrationTest extends VerifoneTest
{
    private $testUrl;
    private $privateKey;
    private $publicKey;
    private $customer;
    private $exec;

    public function setUp(): void
    {
        $this->testUrl = 'https://epayment.test.point.fi/pw/serverinterface';
        $this->privateKey = file_get_contents('Assets/demo-merchant-agreement-private.pem', true);
        $this->publicKey = file_get_contents('Assets/point-e-commerce-test-public-key.pem', true);
        $this->customer = new CustomerImpl(
            'Example',
            'Exemplar',
            '0401234567',
            'example@domain.fi'
        );
        $container = new ExecutorContainer();
        $this->exec = $container->getExecutor('backend');
    }

    public function testGetAvailablePaymentMethods()
    {
        $config = new GetAvailablePaymentMethodsConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Xtreme Viper 27,56 polkupyoraaaaaaaaaaaaaaaaa',
            '1.9.2.2',
            array($this->testUrl),
            '978'
        );

        $service = ServiceFactory::createService($config, 'Backend\GetAvailablePaymentMethodsService');
        $service->insertCustomer($this->customer);

        $responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testCheckAvailability()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );

        $service = ServiceFactory::createService($config, 'Backend\CheckAvailabilityService');
       // $responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testGetPaymentStatus()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );

        $transaction =  new TransactionImpl('visa', '5153569280');

        $service = ServiceFactory::createService($config, 'Backend\GetPaymentStatusService');
        $service->insertCustomer($this->customer);
        $service->insertTransaction($transaction);

        //$responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testGetSavedCreditCards()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );

        $service = ServiceFactory::createService($config, 'Backend\GetSavedCreditCardsService');
        $service->insertCustomer($this->customer);
        
     //   $this->expectException(ResponseCheckFailedException::class);
     //   $responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testRemoveSavedCreditCards()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );

        $payment = new PaymentInfoImpl('', '', '123456789');

        $service = ServiceFactory::createService($config, 'Backend\RemoveSavedCreditCardsService');
        $service->insertCustomer($this->customer);
        $service->insertPaymentInfo($payment);

        //$responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testListTransactionNumbers()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );

        $order = new OrderImpl('123', '', '', '', '', '', '');

        $service = ServiceFactory::createService($config, 'Backend\ListTransactionNumbersService');
        $service->insertOrder($order);
   //     $responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testRefundPayment()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );

        $transaction = new TransactionImpl('visa', '5153569280', '100', '978');

        $service = ServiceFactory::createService($config, 'Backend\RefundPaymentService');
        $service->insertTransaction($transaction);
        //$this->expectException(ResponseCheckFailedException::class);
        //$responseFields = $this->exec->executeService($service, $this->publicKey);
    }

    public function testProcessPayment()
    {
        $config = new BackendConfigurationImpl(
            $this->privateKey,
            'demo-merchant-agreement',
            'Magento',
            '1.9.2.2',
            array($this->testUrl)
        );
        $order = new OrderImpl('asdf123afds', '2016-05-31 10:11:16', '978', '100', '', '', '');
        $paymentInfo = new PaymentInfoImpl('fi_FI', '', '123456789');

        $service = ServiceFactory::createService($config, 'Backend\ProcessPaymentService');
        $service->insertCustomer($this->customer);
        $service->insertOrder($order);
        $service->insertPaymentInfo($paymentInfo);
   //     $this->expectException(ResponseCheckFailedException::class);
   //     $responseFields = $this->exec->executeService($service, $this->publicKey);
    }
}
