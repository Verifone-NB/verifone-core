<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Validation;


use Lamia\Validation\Validation\Interfaces\Validation;
use Verifone\Core\DependencyInjection\Validation\Response\ResponseValidation;

class VerifoneValidation implements CommonValidation
{
    private $fieldValidation;
    private $responseValidation;
    
    public function __construct(Validation $fieldValidation, ResponseValidation $responseValidation)
    {
        $this->fieldValidation = $fieldValidation;
        $this->responseValidation = $responseValidation;
    }

    /**
     * @param array $requestFields
     * @param array $responseFields
     * @param String|bool $publicKey
     */
    public function validate(array $requestFields, array $responseFields = array(), $publicKey = false)
    {
        if ($publicKey !== false) {
            $this->responseValidation->validate($requestFields, $responseFields, $publicKey);
        }
        else {
            $this->fieldValidation->validateFields($requestFields);
        }
    }
}
