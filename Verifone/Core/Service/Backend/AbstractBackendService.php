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
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Service\AbstractService;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration;
use Verifone\Core\Storage\Storage;

/**
 * Class AbstractBackendService
 * @package Verifone\Core\Service\Backend
 * 
 * Contains methods that are in common across all the backend services
 */
abstract class AbstractBackendService extends AbstractService implements BackendService
{
    private $urls;
    private $responseConverter;

    /**
     * AbstractBackendService constructor.
     * sets the timestamp and request id for backend services
     * @param Storage $storage
     * @param BackendConfiguration $configuration
     * @param CryptUtil $crypto
     * @param ResponseConverter $converter
     */
    public function __construct(
        Storage $storage,
        BackendConfiguration $configuration,
        CryptUtil $crypto,
        ResponseConverter $converter
    ) {
        parent::__construct($storage, $configuration, $crypto);
        $this->addToStorage(FieldConfig::REQUEST_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->addToStorage(FieldConfig::REQUEST_ID, strval(hexdec(uniqid())));
        $this->urls = $configuration->getUrls();
        $this->responseConverter = $converter;
    }

    public function getUrls()
    {
        return $this->urls;
    }
    
    public function getResponseConverter()
    {
        return $this->responseConverter;
    }
}
