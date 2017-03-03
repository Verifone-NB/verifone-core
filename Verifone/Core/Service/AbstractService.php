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
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtilImpl;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Storage\Storage;
use Crypt_RSA;

abstract class AbstractService implements Service
{
    const INTERFACE_VERSION_VALUE = '3';
    
    private $storage;
    private $privateKey;
    private $crypto;

    public function __construct(Storage $storage, Configuration $configuration, CryptUtil $crypto)
    {
        $this->crypto = $crypto;
        $this->storage = $storage;
        $this->privateKey = $configuration->getPrivateKey();
        $this->addToStorage(FieldConfigImpl::INTERFACE_VERSION, self::INTERFACE_VERSION_VALUE);
        $this->insertConfiguration($configuration);
    }

    public function insertCustomer(Customer $customer)
    {
        // Does nothing unless overridden in extending class
    }

    public function insertOrder(Order $order)
    {
        // Does nothing unless overridden in extending class
    }

    public function insertProduct(Product $product)
    {
        // Does nothing unless overridden in extending class
    }
    
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        // Does nothing unless overridden in extending class
    }
    
    public function insertTransaction(Transaction $transaction)
    {
        // Does nothing unless overridden in extending class
    }

    public function getFields()
    {
        if ($this->storage->get(FieldConfigImpl::SIGNATURE_ONE) === false) {
            $signature = $this->crypto->generateSignatureOne($this->privateKey, $this->storage->getAsArray());
            $this->addToStorage(FieldConfigImpl::SIGNATURE_ONE, $signature);
        }
        return $this->storage;
    }
    
    private function insertConfiguration(Configuration $configuration)
    {
        $this->addToStorage(FieldConfigImpl::CONFIG_MERCHANT_AGREEMENT_CODE, $configuration->getMerchantAgreementCode());
        $this->addToStorage(FieldConfigImpl::CONFIG_SOFTWARE, $configuration->getSoftware());
        $this->addToStorage(FieldConfigImpl::CONFIG_SOFTWARE_VERSION, $configuration->getSoftwareVersion());
    }
    
    protected function addToStorage($key, $value)
    {
        $this->storage->add($key, $value);
    }
    
    protected function getMerchantAgreementCode()
    {
        return $this->storage->get(FieldConfigImpl::CONFIG_MERCHANT_AGREEMENT_CODE);
    }

    protected function getPaymentTimestamp()
    {
        return $this->storage->get(FieldConfigImpl::PAYMENT_TIMESTAMP);
    }

    protected function calculateTaxPercent($priceInclTax, $priceExclTax)
    {
        if (is_string($priceExclTax) === false || is_numeric($priceExclTax) === false || $priceExclTax === '0') {
            return '0';
        }
        $taxPercent = ($priceInclTax-$priceExclTax) / $priceExclTax;
        return '' . round($taxPercent, 2) * 10000;
    }
}
