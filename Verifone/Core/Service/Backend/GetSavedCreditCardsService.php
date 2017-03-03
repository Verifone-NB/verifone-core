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
use Verifone\Core\Service\AbstractService;
use Verifone\Core\Storage\Storage;

/**
 * Class GetSavedCreditCardsService
 * @package Verifone\Core\Service\Backend
 * A service to get a list of saved payment methods of customer
 */
final class GetSavedCreditCardsService extends AbstractBackendService
{
    const OPERATION_VALUE = 'list-saved-payment-methods';

    public function __construct(
        Storage $storage,
        BackendConfiguration $config,
        CryptUtil $crypto,
        ResponseConverter $responseConverter
    ) {
        parent::__construct($storage, $config, $crypto, $responseConverter);
        $this->addToStorage(FieldConfigImpl::OPERATION, self::OPERATION_VALUE);
    }


    public function insertCustomer(Customer $customer)
    {
        $this->addToStorage(FieldConfigImpl::CUSTOMER_FIRST_NAME, $customer->getFirstName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_LAST_NAME, $customer->getLastName());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_PHONE_NUMBER, $customer->getPhoneNumber());
        $this->addToStorage(FieldConfigImpl::CUSTOMER_EMAIL, $customer->getEmail());
    }
}
