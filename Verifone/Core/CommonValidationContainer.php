<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core;


use Lamia\Validation\ValidationContainer;
use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Exception\ExecutorCreationFailedException;

class CommonValidationContainer
{
    const VALIDATION = 'validation.class';
    const FIELD_VALIDATION = 'fieldValidation.class';
    const BACKEND_RESPONSE_VALIDATION = 'backendResponseValidation.class';
    const FRONTEND_RESPONSE_VALIDATION = 'frontendResponseValidation.class';
    const RESPONSE_VALIDATION_UTILS = 'responseValidationUtils.class';

    const TYPE_BACKEND = 'backend';
    const TYPE_FRONTEND = 'frontend';

    private $parameters = array();
    private $namespace = '\Verifone\Core\\';

    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
        $this->setDefaultParameterValues();
    }
    
    private function setDefaultParameterValues()
    {
        if (!isset($this->parameters[self::FIELD_VALIDATION])) {
            $this->parameters[self::FIELD_VALIDATION] = 'DependencyInjection\Validation\VerifoneFieldValidation';
        }
        if (!isset($this->parameters[self::VALIDATION])) {
            $this->parameters[self::VALIDATION] = 'DependencyInjection\Validation\VerifoneValidation';
        }
        if (!isset($this->parameters[self::BACKEND_RESPONSE_VALIDATION])) {
            $this->parameters[self::BACKEND_RESPONSE_VALIDATION] = 'DependencyInjection\Validation\Response\BackendResponseValidation';
        }
        if (!isset($this->parameters[self::FRONTEND_RESPONSE_VALIDATION])) {
            $this->parameters[self::FRONTEND_RESPONSE_VALIDATION] = 'DependencyInjection\Validation\Response\FrontendResponseValidation';
        }
        if (!isset($this->parameters[self::RESPONSE_VALIDATION_UTILS])) {
            $this->parameters[self::RESPONSE_VALIDATION_UTILS] = 'DependencyInjection\Validation\Response\ResponseValidationUtilsImpl';
        }
    }
    
    public function getValidation(CryptUtil $cryptUtils, $type)
    {
        $fieldValidation = $this->getFieldValidation();
        $validationClass = $this->getAndValidateClassName(self::VALIDATION);
        if ($type === self::TYPE_BACKEND) {
            $responseValidation = $this->getResponseValidation($cryptUtils, self::BACKEND_RESPONSE_VALIDATION);
            return new $validationClass($fieldValidation, $responseValidation);
        }
        if ($type === self::TYPE_FRONTEND) {
            $responseValidation = $this->getResponseValidation($cryptUtils, self::FRONTEND_RESPONSE_VALIDATION);
            return new $validationClass($fieldValidation, $responseValidation);
        }
        throw new ExecutorCreationFailedException("Can't create validation, type must be either backend.type or frontend.type");
    }

    private function getResponseValidation($cryptUtils, $responseValidationClass)
    {
        $responseValidationClass = $this->getAndValidateClassName($responseValidationClass);
        $utilClass = $this->getAndValidateClassName(self::RESPONSE_VALIDATION_UTILS);
        return new $responseValidationClass(new $utilClass($cryptUtils));
    }

    private function getFieldValidation()
    {
        $validationContainer = new ValidationContainer(
            $this->getConfig(),
            array('validation.class' => $this->getAndValidateClassName(self::FIELD_VALIDATION))
        );
        return $validationContainer->getValidation();
    }

    private function getConfig()
    {
        return FieldConfig::getConfig();
    }

    private function getAndValidateClassName($parameter)
    {
        $className = $this->namespace . $this->parameters[$parameter];
        if (!class_exists($className)) {
            throw new ExecutorCreationFailedException('Given class ' . $className . ' does not exist');
        }
        return $className;
    }
}
