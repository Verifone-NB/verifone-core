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

abstract class AbstractFrontendService extends AbstractService implements FrontendService
{

    public function __construct(Storage $storage, FrontendConfiguration $frontEndConfiguration, CryptUtil $crypto)
    {
        parent::__construct($storage, $frontEndConfiguration, $crypto);
        $this->insertFrontendConfiguration($frontEndConfiguration);
    }

    private function insertFrontendConfiguration(FrontendConfiguration $frontendConf)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->addToStorage(FieldConfigImpl::CONFIG_SKIP_CONFIRMATION, $frontendConf->getSkipConfirmation());
        $this->insertRedirectUrls($frontendConf->getRedirectUrls());
    }
    
    private function insertRedirectUrls(RedirectUrls $redirectUrls)
    {
        $this->addToStorage(FieldConfigImpl::CONFIG_CANCEL_URL, $redirectUrls->getCancelUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_ERROR_URL, $redirectUrls->getErrorUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_EXPIRED_URL, $redirectUrls->getExpiredUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_REJECTED_URL, $redirectUrls->getRejectedUrl());
        $this->addToStorage(FieldConfigImpl::CONFIG_SUCCESS_URL, $redirectUrls->getSuccessUrl());
    }
    
    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_LOCALE, $paymentInfo->getLocale());
    }

    public function insertCustomer(Customer $customer)
    {
        $this->addToStorage(FieldConfigImpl::CUSTOMER_FIRST_NAME, $customer->getFirstName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_LAST_NAME, $customer->getLastName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_PHONE_NUMBER, $customer->getPhoneNumber());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_EMAIL, $customer->getEmail());
    }

    protected function addPaymentToken($orderNumber)
    {
        $paymentToken = $this->generatePaymentToken($orderNumber);
        $this->addToStorage(FieldConfigImpl::PAYMENT_TOKEN, $paymentToken);
    }

    private function generatePaymentToken($orderNumber)
    {
        $merchantAgreementCode = $this->getMerchantAgreementCode();
        $timestamp = $this->getPaymentTimestamp();
        $hash = hash('sha256', $merchantAgreementCode . ';' . $orderNumber . ';' . $timestamp);
        return strtoupper(substr($hash, 0, 32));
    }
}
