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
use Verifone\Core\DependencyInjection\Configuration\Backend\GetAvailablePaymentMethodsConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Storage\Storage;

/**
 * Class GetAvailablePaymentMethodsService
 * @package Verifone\Core\Service\Backend
 * 
 * A service for getting a list of available payment methods
 */
final class GetAvailablePaymentMethodsService extends AbstractBackendService
{
    const OPERATION_VALUE = 'list-payment-methods';

    public function __construct(
        Storage $storage,
        GetAvailablePaymentMethodsConfiguration $configuration,
        CryptUtil $crypto,
        ResponseConverter $responseConverter
    ) {
        parent::__construct($storage, $configuration, $crypto, $responseConverter);
        $this->addToStorage(FieldConfig::CONFIG_CURRENCY, $configuration->getCurrency());
        $this->addToStorage(FieldConfig::OPERATION, self::OPERATION_VALUE);
    }
}
