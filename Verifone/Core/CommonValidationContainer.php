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


use Lamia\Validation\Validation\Interfaces\Validation;
use Lamia\Validation\ValidationContainer;
use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Validation\CommonValidation;
use Verifone\Core\DependencyInjection\Validation\Response\ResponseValidation;
use Verifone\Core\Exception\ExecutorCreationFailedException;

/**
 * Class CommonValidationContainer
 * @package Verifone\Core
 * The purpose of this class is to create a common validation interface
 *
 * Creates a common validation interface, through which field and response validation can be accessed. Injects
 * the validation with either selected dependencies or when lacking those, with the default dependencies. ALl the
 * used classes need to be in namespace \Verifone\Core\
 */
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

    /**
     * CommonValidationContainer constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
        $this->setDefaultParameterValues();
    }

    /**
     * Set default implementation classes for the injected dependencies.
     */
    private function setDefaultParameterValues()
    {
        $this->setDefaultParameterValue(self::FIELD_VALIDATION, 'DependencyInjection\Validation\VerifoneFieldValidation');
        $this->setDefaultParameterValue(self::VALIDATION, 'DependencyInjection\Validation\VerifoneValidation');
        $this->setDefaultParameterValue(self::BACKEND_RESPONSE_VALIDATION, 'DependencyInjection\Validation\Response\BackendResponseValidation');
        $this->setDefaultParameterValue(self::FRONTEND_RESPONSE_VALIDATION, 'DependencyInjection\Validation\Response\FrontendResponseValidation');
        $this->setDefaultParameterValue(self::RESPONSE_VALIDATION_UTILS, 'DependencyInjection\Validation\Response\ResponseValidationUtilsImpl');
    }

    /**
     * If the parameter value isn't set, assign given value for it.
     * @param string $name of the parameter field
     * @param string $value to assign for parameter field
     */
    private function setDefaultParameterValue($name, $value)
    {
        if (!isset($this->parameters[$name])) {
            $this->parameters[$name] = $value;
        }
    }

    /**
     * Returns a common validation interface for field and response validation.
     * @param CryptUtil $cryptUtils used crypto utilities in validation
     * @param FieldConfig $config for field configuration to validate with
     * @param string $type to decide what kind of response validation is needed
     * @return CommonValidation
     * @throws ExecutorCreationFailedException if something went wrong trying to create classes
     */
    public function getValidation(CryptUtil $cryptUtils, FieldConfig $config, $type)
    {
        $fieldValidation = $this->getFieldValidation($config);
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

    /**
     * Creates the response validation class
     * @param CryptUtil $cryptUtils for response validation
     * @param string $responseValidationClass name
     * @return ResponseValidation
     * @throws ExecutorCreationFailedException if something went wrong while trying to create the classes
     */
    private function getResponseValidation(CryptUtil $cryptUtils, $responseValidationClass)
    {
        $responseValidationClass = $this->getAndValidateClassName($responseValidationClass);
        $utilClass = $this->getAndValidateClassName(self::RESPONSE_VALIDATION_UTILS);
        return new $responseValidationClass(new $utilClass($cryptUtils));
    }

    /**
     * Create a field validation from field validation module with the field validation container
     * @param FieldConfig $config configuration for field validation
     * @return Validation field validation
     * @throws ExecutorCreationFailedException if something went wrong while trying to create the classes
     */
    private function getFieldValidation(FieldConfig $config)
    {
        $validationContainer = new ValidationContainer(
            $config->getConfig(),
            array('validation.class' => $this->getAndValidateClassName(self::FIELD_VALIDATION))
        );
        return $validationContainer->getValidation();
    }

    /**
     * Validate that class exists and return the full class name including namespace
     * @param string $parameter the configuration parameter field name for the class to access the actual class name
     * @return string full class name including namespace
     * @throws ExecutorCreationFailedException if the class doesn't exist.
     */
    private function getAndValidateClassName($parameter)
    {
        $className = $this->namespace . $this->parameters[$parameter];
        if (!class_exists($className)) {
            throw new ExecutorCreationFailedException('Given class ' . $className . ' does not exist');
        }
        return $className;
    }
}
