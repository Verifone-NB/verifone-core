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

use Lamia\Validation\Validation\Interfaces\Validation;
use Verifone\Core\Converter\Request\RequestConverter;
use Verifone\Core\DependencyInjection\Validation\CommonValidation;
use Verifone\Core\Service\Frontend\FrontendService;

class FrontendServiceExecutor
{
    private $validation;
    private $converter;

    public function __construct(CommonValidation $validation, RequestConverter $converter)
    {
        $this->validation = $validation;
        $this->converter = $converter;
    }

    public function executeService(FrontendService $service, $actionUrl)
    {
        $storage = $service->getFields();
        $this->validation->validate($storage->getAsArray());
        return $this->converter->convert($storage, $actionUrl);
    }
}
