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

use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\DependencyInjection\Service\PaymentInfoImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Address;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Recurring;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Exception\MissingBillingAddressException;
use Verifone\Core\Storage\Storage;

/**
 * Class CreateNewOrderService
 * @package Verifone\Core\Service\Frontend
 * The purpose of this class is to contain and generate request fields for creating a new order.
 */
final class CreateNewOrderService extends AbstractFrontendService
{
    const RECURRING_PAYMENT_VALUE = '1';
    const RECURRING_PERIOD_VALUE = '7';
    
    private $productCounter;

    /**
     * CreateNewOrderService constructor.
     * @param Storage $storage
     * @param FrontendConfiguration $config
     * @param CryptUtil $crypto
     */
    public function __construct(Storage $storage, FrontendConfiguration $config, CryptUtil $crypto)
    {
        parent::__construct($storage, $config, $crypto);
        $this->productCounter = 0;
    }

    /**
     * @param PaymentInfo $paymentInfo
     * Add payment info to order
     */
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        parent::insertPaymentInfo($paymentInfo);
        $this->addToStorage(FieldConfigImpl::PAYMENT_SAVED_METHOD_ID, $paymentInfo->getSavedMethodId());

        if ($paymentInfo->getRecurring() instanceof Recurring) {
            /**
			 * Allow to set save method to SAVE_METHOD_SAVE_ONLY if needed.
			 *
			 * This is useful for subscriptions that start with free trial (0 amount),
			 * so that the card is saved without charging anything from it.
			 *
			 * Otherwise, force the save method to SAVE_METHOD_AUTO_SAVE for recurring payments to work.
			 */
			if (!empty($paymentInfo->getSaveMethod()) && $paymentInfo->getSaveMethod() === PaymentInfoImpl::SAVE_METHOD_SAVE_ONLY) {
				$this->addToStorage(FieldConfigImpl::PAYMENT_SAVE_METHOD, PaymentInfoImpl::SAVE_METHOD_SAVE_ONLY);
			} else {
				$this->addToStorage(FieldConfigImpl::PAYMENT_SAVE_METHOD, PaymentInfoImpl::SAVE_METHOD_AUTO_SAVE);
			}

            $this->insertRecurring($paymentInfo->getRecurring());
        }
        else {
            $this->addToStorage(FieldConfigImpl::PAYMENT_SAVE_METHOD, $paymentInfo->getSaveMethod());
        }
        
        if ($paymentInfo->getSaveMaskedPan() === true) {
            $this->addToStorage(
                FieldConfigImpl::PAYMENT_DYNAMIC_FEEDBACK,
                FieldConfigImpl::PAYMENT_PAN_LAST_2 . ',' . FieldConfigImpl::PAYMENT_PAN_FIRST_6
            );
        }
    }

    /**
     * @param Transaction $transaction
     * add transaction info to order.
     */
    public function insertTransaction(Transaction $transaction)
    {
        $this->addToStorage(FieldConfigImpl::FRONTEND_PAYMENT_METHOD, $transaction->getMethodCode());
    }

    /**
     * @param Recurring $recurring
     * Adds recurring payment information to order
     */
    private function insertRecurring(Recurring $recurring)
    {
        $this->addToStorage(FieldConfigImpl::RECURRING_PAYMENT, self::RECURRING_PAYMENT_VALUE);
        $this->addToStorage(FieldConfigImpl::RECURRING_SUBSCRIPTION_NAME, $recurring->getSubscriptionName());
        $this->addToStorage(FieldConfigImpl::RECURRING_SUBSCRIPTION_CODE, $recurring->getSubscriptionCode());
        $this->addToStorage(FieldConfigImpl::RECURRING_PERIOD, self::RECURRING_PERIOD_VALUE);
    }

    /**
     * @param Order $order
     * Add general order information to order
     */
    public function insertOrder(Order $order)
    {
        parent::insertOrder($order);
        $this->addToStorage(FieldConfigImpl::ORDER_TIMESTAMP, $order->getTimestamp());
        $this->addToStorage(FieldConfigImpl::ORDER_NUMBER, $order->getIdentificator());
        $this->addToStorage(FieldConfigImpl::ORDER_TOTAL_INCL_TAX, $order->getTotalInclTax());
        $this->addToStorage(FieldConfigImpl::ORDER_TOTAL_EXCL_TAX, $order->getTotalExclTax());
        $this->addToStorage(FieldConfigImpl::ORDER_TAX_AMOUNT, $order->getTaxAmount());
        $this->addToStorage(FieldConfigImpl::ORDER_CURRENCY, $order->getCurrency());
        $this->addPaymentToken($order->getIdentificator());
    }

    /**
     * @param Product $product
     * Add a product to order(multiple can be added)
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
     * @param Customer $customer
     * Add customer information to order
     * @throws \Verifone\Core\Exception\MissingBillingAddressException
     */
    public function insertCustomer(Customer $customer)
    {
        parent::insertCustomer($customer);
        if ($customer->getAddress() instanceof Address) {
            $this->insertAddress($customer->getAddress());
        } else {
            throw new MissingBillingAddressException('Billing address is require due to PSD/2 regulation');
        }
    }

    /**
     * @param Address $address
     * Add billing address information to order
     */
    private function insertAddress(Address $address)
    {
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_LINE_1, $address->getLineOne());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_LINE_2, $address->getLineTwo());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_LINE_3, $address->getLineThree());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_CITY, $address->getCity());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_POSTAL, $address->getPostalCode());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_COUNTRY, $address->getCountryCode());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_FIRST_NAME, $address->getFirstName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_LAST_NAME, $address->getLastName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_PHONE_NUMBER, $address->getPhoneNumber());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_ADDRESS_EMAIL, $address->getEmail());
    }

    /**
     * @param Address $address
     * Add delivery address information to order
     */
    public function insertDeliveryAddress(Address $address)
    {
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_LINE_1, $address->getLineOne());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_LINE_2, $address->getLineTwo());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_LINE_3, $address->getLineThree());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_CITY, $address->getCity());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_POSTAL, $address->getPostalCode());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_COUNTRY, $address->getCountryCode());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_FIRST_NAME, $address->getFirstName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_LAST_NAME, $address->getLastName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_PHONE_NUMBER, $address->getPhoneNumber());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_DELIVERY_ADDRESS_EMAIL, $address->getEmail());
    }

    /**
     * @param $key
     * @return string
     * Return product field key with a counter number appended to the end.
     */
    private function getWithCounter($key)
    {
        return $key . $this->productCounter;
    }
}
