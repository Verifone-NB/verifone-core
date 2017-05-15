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
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Storage\Storage;

/**
 * Class GetPaymentStatusService
 * @package Verifone\Core\Service\Backend
 * A service for getting the status of transaction / payment
 */
final class GetPaymentStatusService extends AbstractBackendService
{
    const OPERATION_VALUE = 'get-payment-status';
    // fields that should match in both request and response
    private $matchingFields = array(
        FieldConfigImpl::CONFIG_TRANSACTION,
        FieldConfigImpl::PAYMENT_METHOD
    );


    /**
     * GetPaymentStatusService constructor.
     * @param Storage $storage
     * @param BackendConfiguration $configuration
     * @param CryptUtil $crypto
     * @param ResponseConverter $responseConverter
     */
    public function __construct(
        Storage $storage,
        BackendConfiguration $configuration,
        CryptUtil $crypto,
        ResponseConverter $responseConverter
    ) {
        parent::__construct($storage, $configuration, $crypto, $responseConverter);
        $this->addToStorage(FieldConfigImpl::OPERATION, self::OPERATION_VALUE);
    }

    /**
     * @param Transaction $transaction
     * Insert transaction information of the payment that's status is wanted to check
     */
    public function insertTransaction(Transaction $transaction)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_METHOD, $transaction->getMethodCode());
        $this->addToStorage(FieldConfigImpl::CONFIG_TRANSACTION, $transaction->getNumber());
    }

    /**
     * @return array of fields that need to match in both request and response
     */
    public function getMatchingFields()
    {
        return array_merge(parent::getMatchingFields(), $this->matchingFields);
    }
}
