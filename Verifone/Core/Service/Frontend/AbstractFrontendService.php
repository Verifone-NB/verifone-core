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
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrls;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\Service\AbstractService;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\Storage\Storage;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;

/**
 * Class AbstractFrontendService
 * @package Verifone\Core\Service\Frontend
 * The purpose of this class is to contain frontend (Verifone hosted) information needed for creating the request
 */
abstract class AbstractFrontendService extends AbstractService implements FrontendService
{
    const INTERFACE_VERSION_VALUE = '5';

    /**
     * FrontendService constructor.
     * @param Storage $storage for containing the actual fields
     * @param FrontendConfiguration $frontEndConfiguration fot the configuration values
     * @param CryptUtil $crypto for calculating signatures
     */
    public function __construct(Storage $storage, FrontendConfiguration $frontEndConfiguration, CryptUtil $crypto)
    {
        parent::__construct($storage, $frontEndConfiguration, $crypto);
        $this->insertFrontendConfiguration($frontEndConfiguration);
    }

    /**
     * @param FrontendConfiguration $frontendConf
     * insert configuration information to storage.
     */
    private function insertFrontendConfiguration(FrontendConfiguration $frontendConf)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->addToStorage(FieldConfigImpl::CONFIG_SKIP_CONFIRMATION, $frontendConf->getSkipConfirmation());
        $this->addToStorage(FieldConfigImpl::STYLE_CODE, $frontendConf->getStyleCode());
        $this->insertRedirectUrls($frontendConf->getRedirectUrls());
    }

    /**
     * @param RedirectUrls $redirectUrls
     * insert redirect urls to storage
     */
    private function insertRedirectUrls(RedirectUrls $redirectUrls)
    {
        $this->addToStorage(FieldConfigImpl::CONFIG_CANCEL_URL, $redirectUrls->getCancelUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_ERROR_URL, $redirectUrls->getErrorUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_EXPIRED_URL, $redirectUrls->getExpiredUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_REJECTED_URL, $redirectUrls->getRejectedUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_SUCCESS_URL, $redirectUrls->getSuccessUrl());
    }

    /**
     * @param PaymentInfo $paymentInfo
     * insert payment info to storage
     */
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_LOCALE, $paymentInfo->getLocale());
        $this->addToStorage(FieldConfigImpl::ORDER_NOTE, $paymentInfo->getNote());
    }

    /**
     * @param Customer $customer
     * insert customer info to storage
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
     * @param $orderNumber
     * generate payment token and add insert it to storage
     */
    protected function addPaymentToken($orderNumber)
    {
        $paymentToken = $this->generatePaymentToken($orderNumber);
        $this->addToStorage(FieldConfigImpl::PAYMENT_TOKEN, $paymentToken);
    }

    /**
     * Generates the payment token by combining and hashing with sha256 merchant agreement code,
     * order number and timestamp
     * @param $orderNumber
     * @return string payment token
     */
    private function generatePaymentToken($orderNumber)
    {
        $merchantAgreementCode = $this->getMerchantAgreementCode();
        $timestamp = $this->getPaymentTimestamp();
        $hash = hash('sha256', $merchantAgreementCode . ';' . $orderNumber . ';' . $timestamp);
        return strtoupper(substr($hash, 0, 32));
    }

    protected function getInterfaceVersion()
    {
        return self::INTERFACE_VERSION_VALUE;
    }
}
