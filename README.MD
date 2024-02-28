# Verifone BlueCommerce Payment Gateway Implementation

This repository contains implementation for Verifone BlueCommerce Payment Gateway used in Nordics. Not compatible with the Verifone Global eCommerce Gateway (https://verifone.cloud/)

## Table of Contents
- [1. Installation](#1-installation)
- [2. Basic information](#2-basic-information)
  * [2.1 Contact](#21-contact)
  * [2.2 Configuration - Development mode](#22-configuration---development-mode)
  * [2.3 Configuration - Production mode](#23-configuration---production-mode)
  * [2.4 Delayed URL](#24-delayed-url)
- [3. Usage](#3-usage)
  * [3.1 Fetch available payment methods](#31-fetch-available-payment-methods)
    + [3.1.1 Code example:](#311-code-example-)
  * [3.2 Generate RSA keys](#32-generate-rsa-keys)
    + [3.2.1 Code example](#321-code-example)
  * [3.3 Make payment request](#33-make-payment-request)
    + [3.3.1 Code example](#331-code-example)
  * [3.4 Parse payment response](#34-parse-payment-response)
    + [3.4.1 Code example](#341-code-example)
  * [3.5 Check payment status](#35-check-payment-status)
    + [3.5.1 Code example](#351-code-example)
  * [3.6 Make refund request](#36-make-refund-request)
    + [3.6.1 Code example](#361-code-example)
  * [3.7 Credit Cards](#37-credit-cards)
    + [3.7.1 Add a new card - code example](#371-add-a-new-card---code-example)
    + [3.7.2 Fetch available payment cards - code example](#372-fetch-available-payment-cards---code-example)
    + [3.7.3 Remove payment card - code example](#373-remove-payment-card---code-example)
- [4 Notes](#4-notes)
  * [4.1 Payment Methods](#41-payment-methods)
  * [4.2 Currency](#42-currency)
  * [4.3 Language](#43-language)
  * [4.4 Country](#44-country)

## 1. Installation
```bash
composer require verifone/core
```

## 2. Basic information

### 2.1 Contact 
If you want to contact support or customer service, please send an email to: asiakaspalvelu@verifone.fi

### 2.2 Configuration - Development mode
Library provides functionality for development mode.

For development mode, you have to use a configuration like the below:

**Configuration**|**Value**
:-----:|:-----:
Merchant id|`demo-merchant-agreement`
Hosted interface URL|`https://epayment.test.point.fi/pw/payment`
Server interface URL|`https://epayment.test.point.fi/pw/serverinterface` 

Library provides test keys:
- private key for the shop available in the `Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem`
- public key for the payment service available in the `Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem`

**If you want to use development mode, you have to contact the customer service because our IT department must add the IP to the allowed list**

### 2.3 Configuration - Production mode

For production mode, you have to add a possibility to generate a new key pair and provide the public key into the payment service.
Code example for generating a new pair is available here: [3.2 Generate RSA keys](#32-generate-rsa-keys)

Verifone public key could be found from TCS Client portal by the merchant.

For production mode, you have to use a configuration like in the example below:

**Configuration**|**Value**
:-----:|:-----:
Merchant id|`EC970xxxx`
Hosted interface URL|`https://epayment1.point.fi/pw/payment` and `https://epayment2.point.fi/pw/payment`
Server interface URL|`https://epayment1.point.fi/pw/serverinterface` and `https://epayment2.point.fi/pw/serverinterface `

Merchant id could be found from TCS Client by the merchant.


### 2.4 Delayed URL / S2S Callback
Verifone Service sends a request on the URL with information about the transaction. 
When the response code is different from 200, Verifone Service will repeat the request 24h since the original request. 

Merchant has to provide that delayed URL into Verifone’s TCS Client, so must have a possibility to get that URL from the module.

## 3. Usage
That library has integration and unit tests. If you want, you could find more information about implementation examples there.


### 3.1 Fetch available payment methods
Each merchant has its own payment methods. 
To fetch all available payment methods, you have to use `GetAvailablePaymentMethodsService`. 

After fetch payment methods, you should store them in the system.

#### 3.1.1 Code example:
```php
<?php

require_once('vendor/autoload.php');

use \Verifone\Core\DependencyInjection\Configuration\Backend\GetAvailablePaymentMethodsConfigurationImpl;
use \Verifone\Core\DependencyInjection\CoreResponse\PaymentMethodImpl;
use \Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use \Verifone\Core\ExecutorContainer;
use \Verifone\Core\Executor\BackendServiceExecutor;
use \Verifone\Core\ServiceFactory;
use \Verifone\Core\Service\Backend\GetAvailablePaymentMethodsService;

$configObject = new GetAvailablePaymentMethodsConfigurationImpl(
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'), // merchant private key
    'demo-merchant-agreement', // merchant agreement code
    'testSystem', // software name
    '1.2.3.4', // software version
    ['https://epayment.test.point.fi/pw/serverinterface'], // payment service URLs
    '978', // currency used in system - in example EUR
    false // disable Rsa Blinding
);

/** @var GetAvailablePaymentMethodsService $service */
$service = ServiceFactory::createService($configObject, 'Backend\GetAvailablePaymentMethodsService');
$container = new ExecutorContainer();

/** @var BackendServiceExecutor $exec */
$exec = $container->getExecutor('backend');

/** @var CoreResponse $response */
$response = $exec->executeService($service, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));

if (!$response->getStatusCode()) {
    return null;
}

$body = $response->getBody();
$availableMethods = ['all']; // Default payment method - redirects to page with possibility to select payment methods

/** @var PaymentMethodImpl $item */
foreach ($body as $item) {
    $availableMethods[] = $item->getCode();
}

// Now, you could store $availableMethods in the database.
```

All available payment methods in the notes section: [Payment Methods](#31-payment-methods)



### 3.2 Generate RSA keys
To enable live (production) mode, the merchant must provide the shop public key to the Verifone customer service. 
Public and private keys are using in each request/response action with the Verifone service.

Library provides functionality for generating new key pairs.

#### 3.2.1 Code example
```php
<?php

require_once('vendor/autoload.php');

use \Verifone\Core\DependencyInjection\CryptUtils\RsaKeyGenerator;

$generator = new RsaKeyGenerator();
$result = $generator->generate();

if ($result) {
    // You could store keys in the file or in the database
    // For example
    file_put_contents('shop_public_key.pem', $generator->getPublicKey());
    file_put_contents('shop_private_key.pem', $generator->getPrivateKey());
} else {
    // RSA keys are not generated properly.
}

```

### 3.3 Make payment request

#### 3.3.1 Code example
```php
<?php

require_once('vendor/autoload.php');

use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrlsImpl;
use Verifone\Core\DependencyInjection\Service\AddressImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Service\ProductImpl;
use Verifone\Core\DependencyInjection\Service\TransactionImpl;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\Service\Frontend\CreateNewOrderService;
use Verifone\Core\ServiceFactory;

$urls = new RedirectUrlsImpl(
    'http://www.testikauppa.fi/success', // Return URL for success payment action
    'http://www.testikauppa.fi/rejected', // Return URL for rejected payment action
    'http://www.testikauppa.fi/cancel', // Return URL for cancelled payment action
    'http://www.testikauppa.fi/expired', // Return URL for expired payment action
    'http://www.testikauppa.fi/error' // Return URL for error payment action
);

$address = new AddressImpl(
    'Street 1', // Address - line 1
    '', // Address - line 2
    '', // Address - line 3
    'Helsinki', // City
    '00100', // Postal code
    '246', // Country code
    'John', // First name
    'Doe' // Last name
);

$customer = new CustomerImpl(
    'John', // First name
    'Doe', // Last name
    '0401234567', // Phone number
    'example@domain.fi', // Email number
    $address
);

$order = new OrderImpl(
    '1000000260', // Order identification number
    '2021-05-23 11:58:16', // Order create date
    '978', // Currency code
    '1845', // Order total include tax
    '1500', // Order total exclude tax
    '345' // Order tax
);

$container = new ExecutorContainer(array(ExecutorContainer::REQUEST_CONVERTER => ExecutorContainer::REQUEST_CONVERTER_TYPE_HTML));
$executor = $container->getExecutor('frontend');

$config = new FrontendConfigurationImpl(
    $urls,
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'),
    'demo-merchant-agreement',
    'testSystem',
    '1.2.3.4',
    '1' // skip confirmation page after success payment
);
/**
 * @var CreateNewOrderService $service
 */
$service = ServiceFactory::createService($config, 'Frontend\CreateNewOrderService');

$product1 = new ProductImpl(
    'Test product', // Product name
    '1000', // Unit price, tax excluded. Price for EUR is provided in cents - applicable for all price fields
    '1000', // Price for all units, tax excluded
    '1230', // Price for all units, tax included
    '1', // Quantity
    '0' // Discount for product
);
$product2 = new ProductImpl(
    'Shipment',
    '500',
    '500',
    '615',
    '1',
    '0'
);
$payment = new PaymentInfoImpl(
    'fi_FI', // Payment page language
    PaymentInfoImpl::SAVE_METHOD_AUTO_NO_SAVE // Save method 
);

$transactionInfo = new TransactionImpl(
    'visa', // Payment method 
    '' // Payment number - mostly used for refund
);

$service->insertCustomer($customer);
$service->insertOrder($order);
$service->insertPaymentInfo($payment);
$service->insertTransaction($transactionInfo);
$service->insertProduct($product1);
$service->insertProduct($product2);

$form = $executor->executeService($service, array('https://epayment.test.point.fi/pw/payment'));
```

Now you could attach `$form` on the HTML page, and it automatically will redirect customers into payment service.

If you want to get data as a json (array) you could use that code for create `$container`
```php
$container = new ExecutorContainer(array(ExecutorContainer::REQUEST_CONVERTER => ExecutorContainer::REQUEST_CONVERTER_TYPE_JSON));
```

### 3.4 Parse payment response

Verifone has functionality for resending the payment confirmation. But, for that, you have to create a controller that will handle the same response as the regular one.

#### 3.4.1 Code example
```php
<?php

require_once('vendor/autoload.php');

use \Verifone\Core\DependencyInjection\Service\OrderImpl;
use \Verifone\Core\ServiceFactory;
use \Verifone\Core\Service\FrontendResponse\FrontendResponseServiceImpl;
use \Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use \Verifone\Core\ExecutorContainer;
use \Verifone\Core\DependencyInjection\CoreResponse\PaymentResponseImpl;
use \Verifone\Core\Converter\Response\CoreResponseConverter;

$exampleResponse = [
    'i-f-1-11_interface-version' => '5',
    'i-f-1-3_order-currency-code' => '978',
    'l-f-1-20_order-gross-amount' => '1845',
    'l-f-1-20_transaction-number' => '4516533313',
    's-f-1-10_software-version' => '1.92.1.64j',
    's-f-1-30_payment-method-code' => 'visa',
    's-f-1-36_order-number' => '1000000260',
    's-t-1-26_filing-code' => '210418261011',
    's-t-1-36_order-note' => '',
    's-t-256-256_signature-one' => '6F7E4A2DAACA57C0AA91F933A6C991DDA38B0E17B6438334478846E42D0C9D6BC3FDFEA02B9B7E21296BA51BCC992181E1D79DBF81382EFFE1BF48A5F689F46AD1B7BB35061C9183733A3C0C3E5463759B4F8DE18EAEC2A8F85A89FE5C79EA0D712C79DBF58D91A542AEC8918DF09DA4663A4C1E66BF665099C512CBB45D8BCA',
    's-t-256-256_signature-two' => '7849E6D685D09C1E8A365BFA5CA46519E040BDB6ABDD4739779248FD7E4DE474473BB96FCD1B05E709B97013DEB94390CB7BE9100668B5F162C09A921CF559F469C8FE5F21AB6981246458C7F7735535215C7CF9BCC9D60F5FD1E8AC998D255B24944522EC781BE1E11F7BE58395E646898D18008E85509222154E51B2A7911C',
    't-f-14-19_order-timestamp' => '2021-05-23 11:58:16',
];

/** @var FrontendResponseServiceImpl $service */
$service = ServiceFactory::createResponseService($exampleResponse);

$orderNumber = $service->getOrderNumber();

// order information
$order = new OrderImpl(
    '1000000260',
    '2021-05-23 11:58:16',
    '978',
    '1845',
    '1500',
    '345'
);

/** @var FrontendResponseServiceImpl $service */
$service->insertOrder($order);
$container = new ExecutorContainer(array('responseConversion.class' => 'Converter\Response\FrontendServiceResponseConverter'));
$exec = $container->getExecutor(ExecutorContainer::EXECUTOR_TYPE_FRONTEND_RESPONSE);

/** @var CoreResponse $parseResponse */
$parsedResponse = $exec->executeService($service, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));

/** @var PaymentResponseImpl $body */
$responseBody = $parsedResponse->getBody();
$validate = true;

if ($parsedResponse->getStatusCode() == CoreResponseConverter::STATUS_OK
    && empty($responseBody->getCancelMessage())
) {
    $transactionId = $responseBody->getTransactionNumber();
    $paymentMethod = $responseBody->getPaymentMethodCode();
    
    // Store $paymentMethod and $transactionId in the order data
    // If you need, you could fetch paid amount with: `$responseBody->getOrderGrossAmount() / 100;`
}
```

### 3.5 Check payment status
Sometimes payment verification could take more time for many reasons. 

For example, customers could close the browser tab before returning to the shop or verify if the order is refunded (partially or total).

For that (and similar) cases, you could create (for example) a cron task that fetches all transactions for the order or a controller action.

Cron functionality is not required when you create delayed success action. Verifone Service sends a request on the URL with information
about the transaction. When the response code is different from 200, Verifone Service will repeat the request 24h since the original request. 

#### 3.5.1 Code example
```php
<?php

use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\DependencyInjection\Service\TransactionImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\Service\Backend\GetPaymentStatusService;
use Verifone\Core\Service\Backend\ListTransactionNumbersService;
use Verifone\Core\ServiceFactory;

require_once('vendor/autoload.php');

$config = new BackendConfigurationImpl(
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'),
    'demo-merchant-agreement',
    'testSystem',
    '1.2.3.4',
    ['https://epayment.test.point.fi/pw/serverinterface']
);


$order = new OrderImpl(
    '1000000260',
    '',
    '',
    '',
    '',
    ''
);

/** @var ListTransactionNumbersService $service */
$service = ServiceFactory::createService($config, 'Backend\ListTransactionNumbersService');
$service->insertOrder($order);

$container = new ExecutorContainer();

/** @var BackendServiceExecutor $executor */
$executor = $container->getExecutor('backend');

/** @var CoreResponse $response */
$response = $executor->executeService($service, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));

$transactions = $response->getStatusCode() ? $response->getBody() : [];

/** @var TransactionImpl $transaction */
foreach ($transactions as $transaction) {
    $transaction = new TransactionImpl($transaction->getMethodCode(), $transaction->getNumber());

    /** @var GetPaymentStatusService $service */
    $paymentStatusService = ServiceFactory::createService($config, 'Backend\GetPaymentStatusService');
    $paymentStatusService->insertTransaction($transaction);

    $response = $executor->executeService($paymentStatusService, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));
}
```

### 3.6 Make refund request

#### 3.6.1 Code example
```php
<?php

use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\DependencyInjection\Service\TransactionImpl;
use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\Service\Backend\RefundPaymentService;
use Verifone\Core\ServiceFactory;

require_once('vendor/autoload.php');

$config = new BackendConfigurationImpl(
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'),
    'demo-merchant-agreement',
    'testSystem',
    '1.2.3.4',
    ['https://epayment.test.point.fi/pw/serverinterface']
);

$transaction = new TransactionImpl(
    'visa', // Payment method
    '4516533313', // Transaction code
    '100', // Refund amount
    '978' // Currency code - EUR in the example
);

/** @var RefundPaymentService $service */
$service = ServiceFactory::createService($config, 'Backend\RefundPaymentService');
$service->insertTransaction($transaction);

$container = new ExecutorContainer();
/** @var BackendServiceExecutor $executor */
$executor = $container->getExecutor('backend');

$response = $executor->executeService($service, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));

// if success then store information about refund
```

`4516533313` is the transaction number from the payment response. See `$transactionId` in 3.4.1

### 3.7 Credit Cards

#### 3.7.1 Add a new card - code example
```php
<?php

require_once('vendor/autoload.php');

use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrlsImpl;
use Verifone\Core\DependencyInjection\Service\AddressImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\DependencyInjection\Service\OrderImpl;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\Service\Frontend\AddNewCardService;
use Verifone\Core\ServiceFactory;

$urls = new RedirectUrlsImpl(
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
    'Helsinki',
    '00100',
    '246',
    'John',
    'Doe'
);

$customer = new CustomerImpl(
    'John',
    'Doe',
    '0401234567',
    'example@domain.fi',
    $address
);

$order = new OrderImpl(
    '1000000260',
    '2021-05-23 11:58:16',
    '978',
    '1',
    '1',
    '0'
);

$container = new ExecutorContainer(array(ExecutorContainer::REQUEST_CONVERTER => ExecutorContainer::REQUEST_CONVERTER_TYPE_HTML));
$executor = $container->getExecutor('frontend');

$config = new FrontendConfigurationImpl(
    $urls,
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'),
    'demo-merchant-agreement',
    'testSystem',
    '1.2.3.4',
    '1'
);

/**
 * @var AddNewCardService $service
 */
$service = ServiceFactory::createService($config, 'Frontend\AddNewCardService');

$payment = new PaymentInfoImpl('fi_FI', PaymentInfoImpl::SAVE_METHOD_SAVE_ONLY);

$service->insertCustomer($customer);
$service->insertPaymentInfo($payment);
$service->insertOrder($order);
$form = $executor->executeService($service, array('https://epayment.test.point.fi/pw/payment'));
```
Now you could attach `$form` on the HTML page, and it automatically will redirect customers into payment service.

If you want to get data as a JSON (array), you could use that code to create `$container`.

If you need to add Recurring Card, you could implement it like that:
```php
<?php
require_once('vendor/autoload.php');

use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Service\RecurringImpl;

// Same implementation as above

$recurring = new RecurringImpl('Test', 'test');
$payment = new PaymentInfoImpl(
    'fi_FI', 
    PaymentInfoImpl::SAVE_METHOD_SAVE_ONLY, 
    '', 
    '', 
    false,
    $recurring
);

// Same implementation as above
```

#### 3.7.2 Fetch available payment cards - code example

```php
<?php

require_once('vendor/autoload.php');

use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\Service\Backend\GetSavedCreditCardsService;
use Verifone\Core\ServiceFactory;

$customer = new CustomerImpl(
    'John',
    'Doe',
    '0401234567',
    'example@domain.fi'
);

$config = new BackendConfigurationImpl(
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'),
    'demo-merchant-agreement',
    'testSystem',
    '1.2.3.4',
    ['https://epayment.test.point.fi/pw/serverinterface']
);

/** @var GetSavedCreditCardsService $service */
$service = ServiceFactory::createService($config, 'Backend\GetSavedCreditCardsService');
$service->insertCustomer($customer);

$container = new ExecutorContainer();
/** @var BackendServiceExecutor $executor */
$executor = $container->getExecutor('backend');

$response = $executor->executeService($service, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));
```

#### 3.7.3 Remove payment card - code example

```php
<?php

require_once('vendor/autoload.php');

use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\ExecutorContainer;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\Service\Backend\RemoveSavedCreditCardsService;
use Verifone\Core\ServiceFactory;


$customer = new CustomerImpl(
    'John',
    'Doe',
    '0401234567',
    'example@domain.fi'
);

$config = new BackendConfigurationImpl(
    file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/demo-merchant-agreement-private.pem'),
    'demo-merchant-agreement',
    'testSystem',
    '1.2.3.4',
    ['https://epayment.test.point.fi/pw/serverinterface']
);

$payment = new PaymentInfoImpl(
    '', 
    '', 
    '123456789' // Saved payment method id
);

/** @var RemoveSavedCreditCardsService $service */
$service = ServiceFactory::createService($config, 'Backend\RemoveSavedCreditCardsService');
$service->insertCustomer($customer);
$service->insertPaymentInfo($payment);

$container = new ExecutorContainer();
/** @var BackendServiceExecutor $executor */
$executor = $container->getExecutor('backend');

$response = $executor->executeService($service, file_get_contents('vendor/verifone/core/Verifone/Core/Tests/Integration/Assets/point-e-commerce-test-public-key.pem'));
```

## 4 Notes

### 4.1 Payment Methods
**code**|**type**|**name**
:-----:|:-----:|:-----:
all|ALL|All In One
visa|CARD|Visa
master-card|CARD|Mastercard
dankort|CARD|Dankort
amex|CARD|American Express
diners|CARD|Diners
s-pankki-verkkomaksu|BANK|S-pankki
aktia-maksu|BANK|Aktia
op-pohjola-verkkomaksu|BANK|OP-Pohjola
nordea-e-payment|BANK|Nordea
sampo-web-payment|BANK|Danske Bank
tapiola-verkkomaksu|BANK|Tapiola
handelsbanken-e-payment|BANK|Handelsbanken
alandsbanken-e-payment|BANK|Ålandsbanken
saastopankin-verkkomaksu|BANK|Säästöpankki
pop-pankin-verkkomaksu|BANK|POP Pankki
oma-saastopankin-verkkomaksu|BANK|Oma Säästöpankki
mobilepay|BANK|MobilePay
vipps|BANK|VIPPS
masterpass|BANK|MasterPass
svea-webpay-installment|INVOICE|Svea Osamaksu
svea-webpay-invoice|INVOICE|Svea Lasku
invoice-collector|INVOICE|Collector Lasku
euroloan-invoice|INVOICE|Euroloan Lasku
enterpay-invoice|INVOICE|Enterpay Yrityslasku
paypal|INVOICE|Paypal
afterpay-invoice|INVOICE|Riverty
swish|ELECTRONIC|Swish
siirto|ELECTRONIC|Siirto

### 4.2 Currency
The system uses currency code in the ISO 4217 standard. More information: https://en.wikipedia.org/wiki/ISO_4217

Examples:
- EUR - 978
- SEK - 752
 
### 4.3 Language
Available languages in the payment service:
- fi_FI
- sv_SE
- no_NO 
- dk_DK
- sv_FI
- en_GB

If you use code, which is not available, then en_GB will be used as a default.

### 4.4 Country
The system uses country codes in the numeric ISO 3166-1 standard. More information: https://en.wikipedia.org/wiki/ISO_3166-1_numeric
