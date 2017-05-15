<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit;


use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\GetAvailablePaymentMethodsConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\GetPaymentStatusConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\ProcessPaymentConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\RefundPaymentConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\RemoveSavedCreditCardsConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrlsImpl;
use Verifone\Core\Service\Backend\CheckAvailabilityService;
use Verifone\Core\Service\Backend\GetAvailablePaymentMethodsService;
use Verifone\Core\Service\Backend\GetPaymentStatusService;
use Verifone\Core\Service\Backend\GetSavedCreditCardsService;
use Verifone\Core\Service\Backend\ListTransactionNumbersService;
use Verifone\Core\Service\Backend\ProcessPaymentService;
use Verifone\Core\Service\Backend\RefundPaymentService;
use Verifone\Core\Service\Backend\RemoveSavedCreditCardsService;
use Verifone\Core\Service\Frontend\AddNewCardService;
use Verifone\Core\Service\Frontend\CreateNewOrderService;
use Verifone\Core\Service\FrontendResponse\FrontendResponseServiceImpl;
use Verifone\Core\ServiceFactory;
use Verifone\Core\Exception\ServiceCreationFailedException;

/**
 * Class ServiceFactoryTest
 * @package Verifone\Core\Tests\Unit
 * @codeCoverageIgnore
 */
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestCreateServiceFrontend
     */
    public function testCreateServiceFrontend($serviceName, $class)
    {
        $urls = new RedirectUrlsImpl('http://testi', 'http://testi', 'http://testi', 'http://testi', 'http://testi');
        $config = new FrontendConfigurationImpl($urls, 'a', 'asdf', 'asdf', 'asdf', 1, false);
        $service = ServiceFactory::createService($config, $serviceName);
        $this->assertTrue($service instanceof $class);
    }

    public function providerTestCreateServiceFrontend()
    {
        return array(
            array('Frontend\AddNewCardService', AddNewCardService::class),
            array('Frontend\CreateNewOrderService', CreateNewOrderService::class),
        );
    }

    public function testCreateServiceFrontendWithBlinding()
    {
        $urls = new RedirectUrlsImpl('http://testi', 'http://testi', 'http://testi', 'http://testi', 'http://testi');
        $config = new FrontendConfigurationImpl($urls, 'a', 'asdf', 'asdf', 'asdf', 1, true);
        $service = ServiceFactory::createService($config, 'Frontend\AddNewCardService');
        $this->assertTrue($service instanceof AddNewCardService);
    }

    /**
     * @dataProvider providerTestCreateServiceBackend
     */
    public function testCreateServiceBackend($serviceName, $class)
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, $serviceName);
        $this->assertTrue($service instanceof $class);
    }

    public function providerTestCreateServiceBackend()
    {
        return array(
            array('Backend\GetSavedCreditCardsService', GetSavedCreditCardsService::class),
        );
    }

    public function testCreateServiceFrontendResponse()
    {
        $service = ServiceFactory::createResponseService(array());
        $this->assertTrue($service instanceof FrontendResponseServiceImpl);
    }

    public function testCreateGetAvailablePaymentMethodsService()
    {
        $config = new GetAvailablePaymentMethodsConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'), '1');
        $service = ServiceFactory::createService($config, 'Backend\GetAvailablePaymentMethodsService');
        $this->assertTrue($service instanceof GetAvailablePaymentMethodsService);
    }

    public function testCreateGetPaymentStatusService()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, 'Backend\GetPaymentStatusService');
        $this->assertTrue($service instanceof GetPaymentStatusService);
    }

    public function testCreateRemoveSavedCreditCardsService()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, 'Backend\RemoveSavedCreditCardsService');
        $this->assertTrue($service instanceof RemoveSavedCreditCardsService);
    }

    public function testCreateListTransactionNumbersService()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, 'Backend\ListTransactionNumbersService');
        $this->assertTrue($service instanceof ListTransactionNumbersService);
    }

    public function testCreateRefundPaymentService()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, 'Backend\RefundPaymentService');
        $this->assertTrue($service instanceof RefundPaymentService);
    }

    public function testCreateProcessPaymentService()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, 'Backend\ProcessPaymentService');
        $this->assertTrue($service instanceof ProcessPaymentService);
    }

    public function testCreateCheckAvailabilityService()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $service = ServiceFactory::createService($config, 'Backend\CheckAvailabilityService');
        $this->assertTrue($service instanceof CheckAvailabilityService);
    }

    public function testCreateServiceNullConfig()
    {
        $this->expectException(\TypeError::class);
        $service = ServiceFactory::createService(null, 'Backend\GetAvailablePaymentMethodsService');
    }

    public function testCreateServiceWrongConfig1()
    {
        $urls = new RedirectUrlsImpl('http://testi', 'http://testi', 'http://testi', 'http://testi', 'http://testi');
        $config = new FrontendConfigurationImpl($urls, 'a', 'asdf', 'asdf', 'asdf', array('asfd'), 'aa', '');
        $this->expectException(ServiceCreationFailedException::class);
        $service = ServiceFactory::createService($config, 'Backend\GetAvailablePaymentMethodsService');
    }

    public function testCreateServiceWrongConfig2()
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $this->expectException(ServiceCreationFailedException::class);
        $service = ServiceFactory::createService($config, 'Frontend\AddNewCardService');
    }

    /**
     * @param $name
     *
     * @dataProvider providerTestCreateServiceWrongClassName
     */
    public function testCreateServiceWrongClassName($name)
    {
        $config = new BackendConfigurationImpl('a', 'asdf', 'asdf', 'asdf', array('asfd'));
        $this->expectException(ServiceCreationFailedException::class);
        $service = ServiceFactory::createService($config, $name);
    }

    public function providerTestCreateServiceWrongClassName()
    {
        return array(
            array(''),
            array(null),
            array(true),
            array(false),
            array('BackendGetAvailablePaymentMethodsService'),
            array('Backend'),
            array('Backend\\'),
            array('GetAvailablePaymentMethodsService'),
            array('\GetAvailablePaymentMethodsService'),
            array('\Backend\GetAvailablePaymentMethodsService'),
            array('Backen\GetAvailablePaymentMethodsService'),
            array('Backend\GetAvailablePaymentMethodsServic'),
        );
    }
}
