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


use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\Converter\Response\ResponseConverter;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\Storage\Storage;

/**
 * Class ListTransactionNumbersService
 * @package Verifone\Core\Service\Backend
 * 
 * A service for getting a list of transaction numbers related to a specific order
 */
final class ListTransactionNumbersService extends AbstractBackendService
{
    const OPERATION_VALUE = 'list-transaction-numbers';

    /**
     * ListTransactionNumbersService constructor.
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
        $this->addToStorage(FieldConfig::OPERATION, self::OPERATION_VALUE);
    }

    /**
     * @param Order $order containing order number
     */
    public function insertOrder(Order $order)
    {
        $this->addToStorage(FieldConfig::ORDER_NUMBER, $order->getIdentificator());
    }
}
