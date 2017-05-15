<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Executor;

use Verifone\Core\Converter\Response\ResponseConverter;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Validation\CommonValidation;
use Verifone\Core\Service\FrontendResponse\FrontendResponseService;

/**
 * Class FrontendServiceResponseExecutor
 * @package Verifone\Core\Executor
 * The purpose of the class is to validate and convert verifone frontend response into common format
 */
class FrontendServiceResponseExecutor
{
    private $validation;
    private $converter;

    /**
     * FrontendServiceResponseExecutor constructor.
     * @param CommonValidation $validation
     * @param ResponseConverter $converter
     */
    public function __construct(CommonValidation $validation, ResponseConverter $converter)
    {
        $this->validation = $validation;
        $this->converter = $converter;
    }

    /**
     * @param FrontendResponseService $service
     * @param $publicKey
     * @return CoreResponse
     */
    public function executeService(FrontendResponseService $service, $publicKey)
    {
        $storage = $service->getFields();
        $requestFields = $storage->getAsArray();
        $this->validation->validateResponse($requestFields, $service->getResponse(), $publicKey);
        return $this->converter->convert(new CoreResponse(0, $service->getResponse()));
    }
}
