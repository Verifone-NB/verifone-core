<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\Backend;


use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\Converter\Response\ResponseConverter;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Recurring;
use Verifone\Core\Storage\Storage;

/**
 * Class ProcessPaymentService
 * @package Verifone\Core\Service\Backend
 * A service for processing a payment, also for recurring payments
 */
final class ProcessPaymentService extends AbstractBackendService
{
    const OPERATION_VALUE = 'process-payment';
    const RECURRING_PAYMENT_VALUE = '1';
    
    private $productCounter;

    /**
     * ProcessPaymentService constructor.
     * @param Storage $storage
     * @param BackendConfiguration $config
     * @param CryptUtil $crypto
     * @param ResponseConverter $responseConverter
     */
    public function __construct(
        Storage $storage, 
        BackendConfiguration $config,
        CryptUtil $crypto,
        ResponseConverter $responseConverter
    ) {
        parent::__construct($storage, $config, $crypto, $responseConverter);
        $this->addToStorage(FieldConfig::OPERATION, self::OPERATION_VALUE);
        $this->addToStorage(FieldConfig::PAYMENT_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->productCounter = 0;
    }
    
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        $this->addToStorage(FieldConfig::PAYMENT_LOCALE, $paymentInfo->getLocale());
        $this->addToStorage(FieldConfig::PAYMENT_SAVED_METHOD_ID, $paymentInfo->getSavedMethodId());

        $recurring = $paymentInfo->getRecurring();
        if ($recurring instanceof Recurring) {
            $this->addToStorage(FieldConfig::RECURRING_PAYMENT, self::RECURRING_PAYMENT_VALUE);
            $this->addToStorage(FieldConfig::RECURRING_SUBSCRIPTION_NAME, $recurring->getSubscriptionName());
            $this->addToStorage(FieldConfig::RECURRING_SUBSCRIPTION_CODE, $recurring->getSubscriptionCode());
        }
    }

    public function insertCustomer(Customer $customer)
    {
        $this->addToStorage(FieldConfig::CUSTOMER_FIRST_NAME, $customer->getFirstName());
        $this->addToStorage(FieldConfig::CUSTOMER_LAST_NAME, $customer->getLastName());
        $this->addToStorage(FieldConfig::CUSTOMER_PHONE_NUMBER, $customer->getPhoneNumber());
        $this->addToStorage(FieldConfig::CUSTOMER_EMAIL, $customer->getEmail());
    }

    public function insertOrder(Order $order)
    {
        $this->addToStorage(FieldConfig::ORDER_TIMESTAMP, $order->getTimestamp());
        $this->addToStorage(FieldConfig::ORDER_NUMBER, $order->getIdentificator());
        $this->addToStorage(FieldConfig::ORDER_CURRENCY, $order->getCurrency());
        $this->addToStorage(FieldConfig::ORDER_TOTAL_INCL_TAX, $order->getTotalInclTax());
    }
    
    public function insertProduct(Product $product)
    {
        $taxPercent = $this->calculateTaxPercent($product->getPriceInclTax(), $product->getPriceExclTax());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_NAME), $product->getName());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_UNIT_PRICE), $product->getUnitPriceExclTax());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_PRICE_INCL_TAX), $product->getPriceInclTax());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_PRICE_EXCL_TAX), $product->getPriceExclTax());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_QUANTITY), $product->getQuantity());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_DISCOUNT), $product->getDiscountPercentage());
        $this->addToStorage($this->getWithCounter(FieldConfig::PRODUCT_TAX), $taxPercent);
        $this->productCounter++;
    }

    private function getWithCounter($key)
    {
        return $key . $this->productCounter;
    }
}
