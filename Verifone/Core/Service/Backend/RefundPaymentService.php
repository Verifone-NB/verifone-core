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
 * Class RefundPaymentService
 * @package Verifone\Core\Service\Backend
 * 
 * A service for refunding a payment / certain transaction
 */
final class RefundPaymentService extends AbstractBackendService
{
    const OPERATION_VALUE = 'refund-payment';
    const REFUND_NOTE_VALUE = 'Refund Request';

    // fields that should match in both request and response
    private $matchingFields = array(
        FieldConfigImpl::CONFIG_TRANSACTION,
        FieldConfigImpl::PAYMENT_METHOD
    );

    /**
     * RefundPaymentService constructor.
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
        $this->addToStorage(FieldConfigImpl::ORDER_NOTE, self::REFUND_NOTE_VALUE);
    }

    /**
     * @param Transaction $transaction
     * Information of the transaction that is requested to be refunded
     */
    public function insertTransaction(Transaction $transaction)
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_METHOD, $transaction->getMethodCode());
        $this->addToStorage(FieldConfigImpl::CONFIG_TRANSACTION, $transaction->getNumber());
        $this->addToStorage(FieldConfigImpl::REFUND_AMOUNT, $transaction->getRefundAmount());
        $this->addToStorage(FieldConfigImpl::REFUND_CURRENCY, $transaction->getRefundCurrency());
    }

    /**
     * @return array of fields that need to match in both request and response
     */
    public function getMatchingFields()
    {
        return array_merge(parent::getMatchingFields(), $this->matchingFields);
    }
}
