<?php

/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author  Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service;

use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\DependencyInjection\Configuration\Configuration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Storage\Storage;
use Crypt_RSA;

/**
 * Class AbstractService
 * @package Verifone\Core\Service
 * General functions for the all services
 */
abstract class AbstractService implements Service
{
    const INTERFACE_VERSION_VALUE = '3';
    
    private $storage;
    private $privateKey;
    private $crypto;

    /**
     * AbstractService constructor.
     * @param Storage $storage used for storing the integration fields
     * @param Configuration $configuration containing the configuration field values
     * @param CryptUtil $crypto for calculating the signature
     */
    public function __construct(Storage $storage, Configuration $configuration, CryptUtil $crypto)
    {
        $this->crypto = $crypto;
        $this->storage = $storage;
        $this->privateKey = $configuration->getPrivateKey();
        $this->addToStorage(FieldConfigImpl::INTERFACE_VERSION, $this->getInterfaceVersion());
        $this->insertConfiguration($configuration);
    }

    /**
     * @param Customer $customer
     * Does nothing unless overridden in extending class
     */
    public function insertCustomer(Customer $customer)
    {
        // Does nothing unless overridden in extending class
    }

    /**
     * @param Order $order
     * Does nothing unless overridden in extending class
     */
    public function insertOrder(Order $order)
    {
        // Does nothing unless overridden in extending class
    }

    /**
     * @param Product $product
     * Does nothing unless overridden in extending class
     */
    public function insertProduct(Product $product)
    {
        // Does nothing unless overridden in extending class
    }

    /**
     * @param PaymentInfo $paymentInfo
     * Does nothing unless overridden in extending class
     */
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        // Does nothing unless overridden in extending class
    }

    /**
     * @param Transaction $transaction
     * Does nothing unless overridden in extending class
     */
    public function insertTransaction(Transaction $transaction)
    {
        // Does nothing unless overridden in extending class
    }

    /**
     * @return Storage containing the integration fields and values.
     * Calculates signature one from the values and then returns values as Storage object.
     * After this function is called, more values can't be added to the service because signature one is already
     * calculated and won't be calculated again.
     */
    public function getFields()
    {
        if ($this->storage->get(FieldConfigImpl::SIGNATURE_TWO) === false) {
            $signature2 = $this->crypto->generateSignatureTwo($this->privateKey, $this->storage->getAsArray());
            $this->addToStorage(FieldConfigImpl::SIGNATURE_TWO, $signature2);
        }
        return $this->storage;
    }

    /**
     * @param Configuration $configuration
     * Insert general configuration to storage.
     */
    private function insertConfiguration(Configuration $configuration)
    {
        $this->addToStorage(FieldConfigImpl::CONFIG_MERCHANT_AGREEMENT_CODE, $configuration->getMerchantAgreementCode());
        $this->addToStorage(FieldConfigImpl::CONFIG_SOFTWARE, $configuration->getSoftware());
        $this->addToStorage(FieldConfigImpl::CONFIG_SOFTWARE_VERSION, $configuration->getSoftwareVersion());
    }

    /**
     * A function to add key-value pairs to storage from the sub classes.
     * @param $key
     * @param $value
     */
    protected function addToStorage($key, $value)
    {
        $this->storage->add($key, $value);
    }

    /**
     * A function to access merchant agreement code from subclasses.
     * @return string
     */
    protected function getMerchantAgreementCode()
    {
        return $this->storage->get(FieldConfigImpl::CONFIG_MERCHANT_AGREEMENT_CODE);
    }

    /**
     * A function to access payment timestamp from subclasses.
     * @return string
     */
    protected function getPaymentTimestamp()
    {
        return $this->storage->get(FieldConfigImpl::PAYMENT_TIMESTAMP);
    }

    /**
     * Calculate tax percent from price including taxes and price excluding taxes.
     * @param $priceInclTax
     * @param $priceExclTax
     * @return string tax percent
     */
    protected function calculateTaxPercent($priceInclTax, $priceExclTax)
    {
        if (is_string($priceExclTax) === false || is_numeric($priceExclTax) === false || $priceExclTax === '0') {
            return '0';
        }
        $taxPercent = ($priceInclTax-$priceExclTax) / $priceExclTax;
        return '' . round($taxPercent, 2) * 10000;
    }

    protected function getInterfaceVersion()
    {
        return self::INTERFACE_VERSION_VALUE;
    }
}
