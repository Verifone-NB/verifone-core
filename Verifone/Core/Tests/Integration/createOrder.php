<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core;

require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Frontend/RedirectUrls.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Frontend/RedirectUrlsImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Configuration.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/ConfigurationImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Frontend/FrontendConfiguration.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Frontend/FrontendConfigurationImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Backend/BackendConfiguration.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Configuration/Backend/BackendConfigurationImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Exception/CryptUtilException.php');
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/phpseclib/phpseclib/phpseclib/Math/BigInteger.php');
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/phpseclib/phpseclib/phpseclib/Crypt/Hash.php');
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/phpseclib/phpseclib/phpseclib/Crypt/RSA.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/CryptUtils/Cryptography.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/CryptUtils/SeclibCryptography.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/CryptUtils/CryptUtil.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/CryptUtils/CryptUtilImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Exception/ServiceCreationFailedException.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Storage/Storage.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Storage/ArrayStorage.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Service/Service.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Service/AbstractService.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Service/Frontend/FrontendService.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Service/Frontend/AbstractFrontendService.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Service/Frontend/CreateNewOrderService.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/ServiceFactory.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/Interfaces/Customer.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/Interfaces/Order.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/Interfaces/Product.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/Interfaces/Transaction.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/Interfaces/Recurring.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/Interfaces/PaymentInfo.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/CustomerImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/ProductImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/OrderImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/TransactionImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/RecurringImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/DependencyInjection/Service/PaymentInfoImpl.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Converter/Request/RequestConverter.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Converter/Request/HtmlConverter.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/Executor/FrontendServiceExecutor.php');
require($_SERVER['DOCUMENT_ROOT'] . '/Verifone/Core/ExecutorContainer.php');

use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrlsImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Service\ProductImpl;
use Verifone\Core\Executor\FrontendServiceExecutor;
use Verifone\Core\ExecutorContainer;

$key = file_get_contents('Assets/demo-merchant-agreement-private.pem', true);

$urls = new RedirectUrlsImpl(
    'https://epayment.test.point.fi/test-shop/receipt',
    'https://epayment.test.point.fi/test-shop/cancel',
    'https://epayment.test.point.fi/test-shop/cancel',
    'https://epayment.test.point.fi/test-shop/cancel',
    'https://epayment.test.point.fi/test-shop/cancel'
);
$config = new FrontendConfigurationImpl(
    $urls,
    $key,
    'demo-merchant-agreement',
    'Magento',
    '1.9.2.2',
    array('https://epayment.test.point.fi/pw/payment')
);
$service = ServiceFactory::createService($config, 'Frontend\CreateNewOrderService');

$order = new OrderImpl(
    '1464770962630aa134g',
    '2016-06-01 08:50:16',
    '978',
    '1230',
    '1000',
    '230',
    '0'
);
$customer = new CustomerImpl(
    'Example',
    'Exemplar',
    '+358401234567',
    'example@domain.fi'
);
$product1 = new ProductImpl(
    'Parvekeovi Patio',
    '1000',
    '1000',
    '1230',
    '1',
    '0'
);

$payment = new PaymentInfoImpl('en_GB', '0');

$service->insertCustomer($customer);
$service->insertOrder($order);
$service->insertProduct($product1);
$service->insertPaymentInfo($payment);

$container = new ExecutorContainer();
$exec = $container->getExecutor('frontend');
$form = $exec->executeService($service, 'https://epayment.test.point.fi/pw/payment');
print_r($form);
