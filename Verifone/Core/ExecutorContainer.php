<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 * @author     Szymon Nosal <simon@lamia.fi>
 *
 */

namespace Verifone\Core;

use Lamia\Validation\Validation\Interfaces\Validation;
use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\Converter\Request\RequestConverter;
use Verifone\Core\Converter\Response\ResponseConverter;
use Verifone\Core\DependencyInjection\CryptUtils\Cryptography;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Transporter\TransportationWrapper;
use Verifone\Core\DependencyInjection\Validation\CommonValidation;
use Verifone\Core\DependencyInjection\Validation\Response\ResponseValidation;
use Verifone\Core\DependencyInjection\Validation\Response\ResponseValidationUtils;
use Verifone\Core\Exception\ExecutorCreationFailedException;
use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\Executor\FrontendServiceExecutor;
use Verifone\Core\Executor\FrontendServiceResponseExecutor;
use Verifone\Core\Transport\Transport;

/**
 * Class ExecutorContainer
 * @package Verifone\Core
 * The purpose of this class is to create an executor of wanted type and inject it's dependencies.
 *
 * Creates an executor of type backend, frontend or frontend executor according to wanted type parameter. Inject's
 * the executor with either selected dependencies or when lacking those, with the default dependencies. ALl the
 * used classes need to be in namespace \Verifone\Core\
 */
class ExecutorContainer
{
    // Cryptography wrapper impelmentation
    const CRYPTOGRAPHY = 'cryptography.class';
    // Crypt utils implementation (cryptutils uses crypto wrapper to do needed stuff)
    const CRYPTUTILS = 'cryptUtils.class';
    // Transport wrapper implementation
    const TRANSPORTWRAPPER = 'transportWrapper.class';
    // Transportation wrapper implementation (uses transport wrapper to access transportation)
    const TRANSPORTATION = 'transportation.class';
    // Response converter implementation to convert response to wanted format
    const RESPONSE_CONVERTER = 'responseConversion.class';
    // Request converter implementation to convert the request to wanted format
    const REQUEST_CONVERTER = 'requestConversion.class';
    // Field configuration
    const FIELD_CONFIGURATION = 'fieldConfiguration.class';

    const REQUEST_CONVERTER_TYPE_JSON = 'json';
    const REQUEST_CONVERTER_TYPE_HTML = 'html';
    const EXECUTOR_TYPE_BACKEND = 'backend';
    const EXECUTOR_TYPE_FRONTEND = 'frontend';
    const EXECUTOR_TYPE_FRONTEND_RESPONSE = 'frontendResponse';

    // parameters for dependency injection
    private $parameters = array();
    // the namespace for used classes
    private $namespace = '\Verifone\Core\\';
    // cache for executors.
    private static $shared = array();

    /**
     * ExecutorContainer constructor.
     * @param array $parameters possible changes for the dependency injection can be injected here.
     * Only classes from namespace \Verifone\Core can be injected.
     * Possible fields:
     * validation.class => The common validation implementation, should implement interface @var CommonValidation
     * fieldValidation.class => The field validation injected to common validation,interface @var Validation
     * backendResponseValidation.class => Response validation to common validation in case of backend validation @var ResponseValidation
     * frontendResponseValidation.class => Response validation to common validation in case of frontend validation @var ResponseValidation
     * responseValidationUtils.class => Validation utils injected to response validation. @var ResponseValidationUtils
     * fieldConfiguration.class => Field configuration for validatio. @var FieldConfig
     * cryptography.class => Cryptography wrapper for chosen cryptography library @var Cryptography
     * cryptUtils.class => Crypt utils for using the cryptowrapper in needed ways @var CryptUtil
     * transportWrapper.class => Transportation wrapper for chosen mode of transport @var TransportationWrapper
     * transportation.class => Transportation logic needed for transporting with chosen wrapper. @var Transport
     * responseConversion.class => Response converter for converting the response to needed format. @var ResponseConverter
     * requestConversion.class => Request converter for converting the request to needed format. @var RequestConverter
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
        $this->setDefaultParameterValue(self::CRYPTOGRAPHY, 'DependencyInjection\CryptUtils\SeclibCryptography');
        $this->setDefaultParameterValue(self::CRYPTUTILS, 'DependencyInjection\CryptUtils\CryptUtilImpl');
        $this->setDefaultParameterValue(self::TRANSPORTWRAPPER, 'DependencyInjection\Transporter\LamiaHttpClientWrapper');
        $this->setDefaultParameterValue(self::TRANSPORTATION, 'Transport\TransportImpl');
        $this->setDefaultParameterValue(self::RESPONSE_CONVERTER, 'Converter\Response\ArrayResponseConverter');
        $this->setDefaultParameterValue(self::REQUEST_CONVERTER, 'Converter\Request\ArrayConverter');
        $this->setDefaultParameterValue(self::FIELD_CONFIGURATION, 'Configuration\FieldConfigImpl');

        if ($this->parameters[self::REQUEST_CONVERTER] == self::REQUEST_CONVERTER_TYPE_JSON) {
            $this->parameters[self::REQUEST_CONVERTER] = 'Converter\Request\JsonConverter';
        }
        else if ($this->parameters[self::REQUEST_CONVERTER] == self::REQUEST_CONVERTER_TYPE_HTML) {
            $this->parameters[self::REQUEST_CONVERTER] = 'Converter\Request\HtmlConverter';
        }
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
     * Get executor for the given type
     *
     * Returns an executor of given type. Possible type values are frontend, frontendResponse and backend.
     * If type something else than frontend or frontendResponse, backend executor is returned as default.
     * @param string $type possible options frontend, frontendResponse, backend.
     * @return mixed|BackendServiceExecutor|FrontendServiceExecutor|FrontendServiceResponseExecutor
     * @throws ExecutorCreationFailedException
     */
    public function getExecutor($type)
    {
        $cryptutil = $this->getCryptography();
        if ($type === self::EXECUTOR_TYPE_FRONTEND) {
            return $this->getFrontendExecutor($cryptutil);
        }
        else if ($type === self::EXECUTOR_TYPE_FRONTEND_RESPONSE) {
            return $this->getFrontendResponseExecutor($cryptutil);
        }
        else {
            return $this->getBackendExecutor($cryptutil);
        }
    }

    /**
     * @param CryptUtil $cryptUtil
     * @return mixed|FrontendServiceResponseExecutor
     * @throws ExecutorCreationFailedException
     */
    private function getFrontendResponseExecutor(CryptUtil $cryptUtil)
    {
        if (isset(self::$shared[self::EXECUTOR_TYPE_FRONTEND_RESPONSE])) {
            return self::$shared[self::EXECUTOR_TYPE_FRONTEND_RESPONSE];
        }

        $config = $this->getConfig();
        $validation = $this->getValidation($cryptUtil, $config, self::EXECUTOR_TYPE_FRONTEND);
        $converter = $this->getResponseConverter();
        $exec = new FrontendServiceResponseExecutor($validation, $converter);
        return self::$shared[self::EXECUTOR_TYPE_FRONTEND_RESPONSE] = $exec;
    }

    /**
     * @param CryptUtil $cryptutil
     * @return mixed|BackendServiceExecutor
     * @throws ExecutorCreationFailedException
     */
    private function getBackendExecutor(CryptUtil $cryptutil)
    {
        if (isset(self::$shared[self::EXECUTOR_TYPE_BACKEND])) {
            return self::$shared[self::EXECUTOR_TYPE_BACKEND];
        }
        $config = $this->getConfig();
        $validation = $this->getValidation($cryptutil, $config, self::EXECUTOR_TYPE_BACKEND);
        $transport = $this->getTransport();
        $converter = $this->getResponseConverter();
        $exec =  new BackendServiceExecutor($validation, $cryptutil, $transport, $converter, $config);
        return self::$shared[self::EXECUTOR_TYPE_BACKEND] = $exec;
    }

    /**
     * @param CryptUtil $cryptutil
     * @return mixed|FrontendServiceExecutor
     * @throws ExecutorCreationFailedException
     */
    private function getFrontendExecutor(CryptUtil $cryptutil)
    {
        if (isset(self::$shared[self::EXECUTOR_TYPE_FRONTEND])) {
            return self::$shared[self::EXECUTOR_TYPE_FRONTEND];
        }

        $config = $this->getConfig();
        $validation = $this->getValidation($cryptutil, $config, self::EXECUTOR_TYPE_FRONTEND);
        $converter = $this->getRequestConverter();
        $exec = new FrontendServiceExecutor($validation, $converter);
        return self::$shared[self::EXECUTOR_TYPE_FRONTEND] = $exec;
    }

    /**
     * @param CryptUtil $cryptUtil
     * @param FieldConfig $config
     * @param string $responseValidationType
     * @return mixed
     * @throws ExecutorCreationFailedException
     */
    private function getValidation(CryptUtil $cryptUtil, FieldConfig $config, $responseValidationType)
    {
        $validation = new CommonValidationContainer($this->parameters);
        return $validation->getValidation($cryptUtil, $config, $responseValidationType);
    }

    /**
     * @return CryptUtil
     * @throws ExecutorCreationFailedException if class doesn't exist
     */
    private function getCryptography()
    {
        $cryptoClass = $this->getAndValidateClassName(self::CRYPTOGRAPHY);
        $cryptUtilClass = $this->getAndValidateClassName(self::CRYPTUTILS);
        $cryptography = new $cryptoClass();
        return new $cryptUtilClass($cryptography);
    }

    /**
     * @return Transport
     * @throws ExecutorCreationFailedException if class doesn't exist
     */
    private function getTransport()
    {
        $transportWrapperClassName = $this->getAndValidateClassName(self::TRANSPORTWRAPPER);
        $transportClass = $this->getAndValidateClassName(self::TRANSPORTATION);
        $transportWrapper = new $transportWrapperClassName();
        return new $transportClass($transportWrapper);
    }

    /**
     * @return ResponseConverter
     * @throws ExecutorCreationFailedException if class doesn't exist
     */
    private function getResponseConverter()
    {
        $converterClassName = $this->getAndValidateClassName(self::RESPONSE_CONVERTER);
        return new $converterClassName();
    }

    /**
     * @return RequestConverter
     * @throws ExecutorCreationFailedException if class doesn't exist
     */
    private function getRequestConverter()
    {
        $converterClassName = $this->getAndValidateClassName(self::REQUEST_CONVERTER);
        return new $converterClassName();
    }

    /**
     * @return FieldConfig
     * @throws ExecutorCreationFailedException
     */
    private function getConfig()
    {
        $className = $this->getAndValidateClassName(self::FIELD_CONFIGURATION);
        return new $className();
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
