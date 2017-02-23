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

class FrontendServiceResponseExecutor
{
    private $validation;
    private $converter;

    public function __construct(CommonValidation $validation, ResponseConverter $converter)
    {
        $this->validation = $validation;
        $this->converter = $converter;
    }

    public function executeService(FrontendResponseService $service, $publicKey)
    {
        $storage = $service->getFields();
        $requestFields = $storage->getAsArray();
        $this->validation->validate($requestFields, $service->getResponse(), $publicKey);
        return $this->converter->convert(new CoreResponse(0, $service->getResponse()));
    }
}
