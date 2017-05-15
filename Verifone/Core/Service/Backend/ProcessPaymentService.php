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


use Verifone\Core\Configuration\FieldConfigImpl;
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
    // Fields that need to match in both request and response
    private $matchingFields = array(
        FieldConfigImpl::ORDER_NUMBER,
        FieldConfigImpl::ORDER_TIMESTAMP,
        FieldConfigImpl::ORDER_CURRENCY,
        FieldConfigImpl::ORDER_TOTAL_INCL_TAX
    );

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
        $this->addToStorage(FieldConfigImpl::OPERATION, self::OPERATION_VALUE);
        $this->addToStorage(FieldConfigImpl::PAYMENT_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->productCounter = 0;
    }

    /**
     * @param PaymentInfo $paymentInfo
     * Payment info of the payment
     */
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_LOCALE, $paymentInfo->getLocale());
        $this->addToStorage(FieldConfigImpl::PAYMENT_SAVED_METHOD_ID, $paymentInfo->getSavedMethodId());

        $recurring = $paymentInfo->getRecurring();
        if ($recurring instanceof Recurring) {
            $this->addToStorage(FieldConfigImpl::RECURRING_PAYMENT, self::RECURRING_PAYMENT_VALUE);
            $this->addToStorage(FieldConfigImpl::RECURRING_SUBSCRIPTION_NAME, $recurring->getSubscriptionName());
            $this->addToStorage(FieldConfigImpl::RECURRING_SUBSCRIPTION_CODE, $recurring->getSubscriptionCode());
        }

        if ($paymentInfo->getSaveMaskedPan() === true) {
            $this->addToStorage(
                FieldConfigImpl::PAYMENT_DYNAMIC_FEEDBACK,
                FieldConfigImpl::PAYMENT_PAN_LAST_2 . ',' . FieldConfigImpl::PAYMENT_PAN_FIRST_6
            );
        }
    }

    /**
     * @param Customer $customer
     * Customer info of the payment
     */
    public function insertCustomer(Customer $customer)
    {
        $this->addToStorage(FieldConfigImpl::CUSTOMER_FIRST_NAME, $customer->getFirstName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_LAST_NAME, $customer->getLastName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_PHONE_NUMBER, $customer->getPhoneNumber());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_EMAIL, $customer->getEmail());
        if ($customer->getExternalId() != '') {
            $this->addToStorage(FieldConfigImpl::CUSTOMER_EXTERNAL_ID, $customer->getExternalId());
        }
    }

    /**
     * @param Order $order
     * Order info for the payment
     */
    public function insertOrder(Order $order)
    {
        $this->addToStorage(FieldConfigImpl::ORDER_TIMESTAMP, $order->getTimestamp());
        $this->addToStorage(FieldConfigImpl::ORDER_NUMBER, $order->getIdentificator());
        $this->addToStorage(FieldConfigImpl::ORDER_CURRENCY, $order->getCurrency());
        $this->addToStorage(FieldConfigImpl::ORDER_TOTAL_INCL_TAX, $order->getTotalInclTax());
    }

    /**
     * @param Product $product
     * Adding product for the payment (multiple can be added by calling this function multiple times)
     */
    public function insertProduct(Product $product)
    {
        $taxPercent = $this->calculateTaxPercent($product->getPriceInclTax(), $product->getPriceExclTax());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_NAME), $product->getName());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_UNIT_PRICE), $product->getUnitPriceExclTax());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_PRICE_INCL_TAX), $product->getPriceInclTax());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_PRICE_EXCL_TAX), $product->getPriceExclTax());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_QUANTITY), $product->getQuantity());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_DISCOUNT), $product->getDiscountPercentage());
        $this->addToStorage($this->getWithCounter(FieldConfigImpl::PRODUCT_TAX), $taxPercent);
        $this->productCounter++;
    }

    /**
     * @param $key
     * @return string
     */
    private function getWithCounter($key)
    {
        return $key . $this->productCounter;
    }

    /**
     * @return array of fields that need to match in both request and response
     */
    public function getMatchingFields()
    {
        return array_merge(parent::getMatchingFields(), $this->matchingFields);
    }
}
