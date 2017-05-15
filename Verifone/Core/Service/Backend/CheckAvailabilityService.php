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
use Verifone\Core\DependencyInjection\Configuration\Backend\GetAvailablePaymentMethodsConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Storage\Storage;

/**
 * Class GetAvailablePaymentMethodsService
 * @package Verifone\Core\Service\Backend
 * 
 * A service for checking the availability of given interface url.
 */
final class CheckAvailabilityService extends AbstractBackendService
{
    const OPERATION_VALUE = 'is-available';

    /**
     * CheckAvailabilityService constructor.
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
}
