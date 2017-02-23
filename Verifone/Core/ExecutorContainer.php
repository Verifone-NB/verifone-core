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
use Lamia\Validation\ValidationContainer;
use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Exception\ExecutorCreationFailedException;
use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\Executor\FrontendServiceExecutor;
use Verifone\Core\Executor\FrontendServiceResponseExecutor;

class ExecutorContainer
{
    const VALIDATION = 'validation.class';
    const FIELD_VALIDATION = 'fieldValidation.class';
    const BACKEND_RESPONSE_VALIDATION = 'backendResponseValidation.class';
    const FRONTEND_RESPONSE_VALIDATION = 'frontendResponseValidation.class';
    const RESPONSE_VALIDATION_UTILS = 'responseValidationUtils.class';
    const CRYPTOGRAPHY = 'cryptography.class';
    const CRYPTUTILS = 'cryptUtils.class';
    const TRANSPORTWRAPPER = 'transportWrapper.class';
    const TRANSPORTATION = 'transportation.class';
    const RESPONSE_CONVERTER = 'responseConversion.class';
    const REQUEST_CONVERTER = 'requestConversion.class';
    
    const TYPE_JSON = 'json';
    const TYPE_HTML = 'html';
    const TYPE_BACKEND = 'backend';
    const TYPE_FRONTEND = 'frontend';
    const TYPE_FRONTEND_RESPONSE = 'frontendResponse';

    private $parameters = array();
    private $namespace = '\Verifone\Core\\';
    private static $shared = array();

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
        if (!isset($this->parameters[self::CRYPTOGRAPHY])) {
            $this->parameters[self::CRYPTOGRAPHY] = 'DependencyInjection\CryptUtils\SeclibCryptography';
        }
        if (!isset($this->parameters[self::CRYPTUTILS])) {
            $this->parameters[self::CRYPTUTILS] = 'DependencyInjection\CryptUtils\CryptUtilImpl';
        }
        if (!isset($this->parameters[self::TRANSPORTWRAPPER])) {
            $this->parameters[self::TRANSPORTWRAPPER] = 'DependencyInjection\Transporter\LamiaHttpClientWrapper';
        }
        if (!isset($this->parameters[self::TRANSPORTATION])) {
            $this->parameters[self::TRANSPORTATION] = 'Transport\TransportImpl';
        }
        if (!isset($this->parameters[self::RESPONSE_CONVERTER])) {
            $this->parameters[self::RESPONSE_CONVERTER] = 'Converter\Response\ArrayResponseConverter';
        }

        if (!isset($this->parameters[self::REQUEST_CONVERTER])) {
            $this->parameters[self::REQUEST_CONVERTER] = 'Converter\Request\ArrayConverter';
        }
        else {
            if ($this->parameters[self::REQUEST_CONVERTER] == self::TYPE_JSON) {
                $this->parameters[self::REQUEST_CONVERTER] = 'Converter\Request\JsonConverter';
            }
            elseif ($this->parameters[self::REQUEST_CONVERTER] == self::TYPE_HTML) {
                $this->parameters[self::REQUEST_CONVERTER] = 'Converter\Request\HtmlConverter';
            }
        }
    }

    public function getExecutor($type)
    {
        $cryptutil = $this->getCryptography();
        if ($type === self::TYPE_FRONTEND) {
            return $this->getFrontendExecutor($cryptutil);
        }
        else if ($type === self::TYPE_FRONTEND_RESPONSE) {
            return $this->getFrontendResponseExecutor($cryptutil);
        }
        else {
            return $this->getBackendExecutor($cryptutil);
        }
    }

    private function getFrontendResponseExecutor(CryptUtil $cryptUtil)
    {
        if (isset(self::$shared[self::TYPE_FRONTEND_RESPONSE])) {
            return self::$shared[self::TYPE_FRONTEND_RESPONSE];
        }

        $validation = $this->getValidation($cryptUtil, self::TYPE_FRONTEND);
        $converter = $this->getResponseConverter();
        $exec = new FrontendServiceResponseExecutor($validation, $converter);
        return self::$shared[self::TYPE_FRONTEND_RESPONSE] = $exec;
    }

    private function getBackendExecutor(CryptUtil $cryptutil)
    {
        if (isset(self::$shared[self::TYPE_BACKEND])) {
            return self::$shared[self::TYPE_BACKEND];
        }
        $validation = $this->getValidation($cryptutil, self::TYPE_BACKEND);
        $transport = $this->getTransport();
        $converter = $this->getResponseConverter();
        $exec =  new BackendServiceExecutor($validation, $cryptutil, $transport, $converter);
        return self::$shared[self::TYPE_BACKEND] = $exec;
    }

    private function getFrontendExecutor(CryptUtil $cryptutil)
    {
        if (isset(self::$shared[self::TYPE_FRONTEND])) {
            return self::$shared[self::TYPE_FRONTEND];
        }

        $validation = $this->getValidation($cryptutil, self::TYPE_FRONTEND);
        $converter = $this->getRequestConverter();
        $exec = new FrontendServiceExecutor($validation, $converter);
        return self::$shared[self::TYPE_FRONTEND] = $exec;
    }

    private function getValidation(CryptUtil $cryptUtil, $type)
    {
        $validation = new CommonValidationContainer($this->parameters);
        return $validation->getValidation($cryptUtil, $type);
    }


    private function getCryptography()
    {
        $cryptoClass = $this->getAndValidateClassName(self::CRYPTOGRAPHY);
        $cryptUtilClass = $this->getAndValidateClassName(self::CRYPTUTILS);
        $cryptography = new $cryptoClass();
        return new $cryptUtilClass($cryptography);
    }
    
    private function getTransport()
    {
        $transportWrapperClassName = $this->getAndValidateClassName(self::TRANSPORTWRAPPER);
        $transportClass = $this->getAndValidateClassName(self::TRANSPORTATION);
        $transportWrapper = new $transportWrapperClassName();
        return new $transportClass($transportWrapper);
    }

    private function getResponseConverter()
    {
        $converterClassName = $this->getAndValidateClassName(self::RESPONSE_CONVERTER);
        return new $converterClassName();
    }

    private function getRequestConverter()
    {
        $converterClassName = $this->getAndValidateClassName(self::REQUEST_CONVERTER);
        return new $converterClassName();
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
