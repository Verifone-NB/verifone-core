<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\Frontend;

use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Address;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Recurring;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Storage\Storage;

final class CreateNewOrderService extends AbstractFrontendService
{
    const RECURRING_PAYMENT_VALUE = '1';
    const RECURRING_PERIOD_VALUE = '7';
    
    private $productCounter;
    
    public function __construct(Storage $storage, FrontendConfiguration $config, CryptUtil $crypto)
    {
        parent::__construct($storage, $config, $crypto);
        $this->productCounter = 0;
    }
    
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        parent::insertPaymentInfo($paymentInfo);
        $this->addToStorage(FieldConfig::PAYMENT_SAVED_METHOD_ID, $paymentInfo->getSavedMethodId());

        if ($paymentInfo->getRecurring() instanceof Recurring) {
            $this->addToStorage(FieldConfig::PAYMENT_SAVE_METHOD, '1');
            $this->insertRecurring($paymentInfo->getRecurring());
        }
        else {
            $this->addToStorage(FieldConfig::PAYMENT_SAVE_METHOD, $paymentInfo->getSaveMethod());
        }
    }
    
    public function insertTransaction(Transaction $transaction)
    {
        $this->addToStorage(FieldConfig::FRONTEND_PAYMENT_METHOD, $transaction->getMethodCode());
    }

    private function insertRecurring(Recurring $recurring)
    {
        $this->addToStorage(FieldConfig::RECURRING_PAYMENT, self::RECURRING_PAYMENT_VALUE);
        $this->addToStorage(FieldConfig::RECURRING_SUBSCRIPTION_NAME, $recurring->getSubscriptionName());
        $this->addToStorage(FieldConfig::RECURRING_SUBSCRIPTION_CODE, $recurring->getSubscriptionCode());
        $this->addToStorage(FieldConfig::RECURRING_PERIOD, self::RECURRING_PERIOD_VALUE);
    }
    

    public function insertOrder(Order $order)
    {
        parent::insertOrder($order);
        $this->addToStorage(FieldConfig::ORDER_TIMESTAMP, $order->getTimestamp());
        $this->addToStorage(FieldConfig::ORDER_NUMBER, $order->getIdentificator());
        $this->addToStorage(FieldConfig::ORDER_TOTAL_INCL_TAX, $order->getTotalInclTax());
        $this->addToStorage(FieldConfig::ORDER_TOTAL_EXCL_TAX, $order->getTotalExclTax());
        $this->addToStorage(FieldConfig::ORDER_TAX_AMOUNT, $order->getTaxAmount());
        $this->addToStorage(FieldConfig::ORDER_CURRENCY, $order->getCurrency());
        $this->addPaymentToken($order->getIdentificator());
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

    public function insertCustomer(Customer $customer)
    {
        parent::insertCustomer($customer);
        if ($customer->getAddress() instanceof Address) {
            $this->insertAddress($customer->getAddress());
        }
    }

    private function insertAddress(Address $address)
    {
        $this->addToStorage(FieldConfig::CUSTOMER_ADDRESS_LINE_1, $address->getLineOne());
        $this->addToStorage(FieldConfig::CUSTOMER_ADDRESS_LINE_2, $address->getLineTwo());
        $this->addToStorage(FieldConfig::CUSTOMER_ADDRESS_LINE_3, $address->getLineThree());
        $this->addToStorage(FieldConfig::CUSTOMER_ADDRESS_CITY, $address->getCity());
        $this->addToStorage(FieldConfig::CUSTOMER_ADDRESS_POSTAL, $address->getPostalCode());
        $this->addToStorage(FieldConfig::CUSTOMER_ADDRESS_COUNTRY, $address->getCountryCode());
    }

    private function getWithCounter($key)
    {
        return $key . $this->productCounter;
    }
}
