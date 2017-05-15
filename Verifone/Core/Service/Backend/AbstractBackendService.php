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
    // fields that should match in both the backend request and response
    private $matchingFields = array(
        FieldConfigImpl::OPERATION,
        FieldConfigImpl::REQUEST_ID
    );

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
        $this->addToStorage(FieldConfigImpl::REQUEST_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->addToStorage(FieldConfigImpl::REQUEST_ID, strval(hexdec(uniqid())));
        $this->urls = $configuration->getUrls();
        $this->responseConverter = $converter;
    }

    /**
     * @return array of urls the request is tried to send to
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @return ResponseConverter for converting the verifone response to general response
     */
    public function getResponseConverter()
    {
        return $this->responseConverter;
    }

    /**
     * @return array of fields that should match in both the request and the response.
     */
    public function getMatchingFields()
    {
        return $this->matchingFields;
    }
}
