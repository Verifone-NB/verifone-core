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


use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\Converter\Response\CoreResponseConverter;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration;
use Verifone\Core\DependencyInjection\Configuration\Configuration;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtilImpl;
use Verifone\Core\DependencyInjection\CryptUtils\SeclibCryptography;
use Verifone\Core\Exception\ServiceCreationFailedException;
use Verifone\Core\Service\Service;
use Verifone\Core\Storage\ArrayStorage;

/**
 * Class ServiceFactory
 * @package Verifone\Core
 * The purpose of this class is to create a wanted service for the requester.
 *
 * The service factory class provides backend, frontend and frontendResponse services for the request according to
 * used method and given parameters. Service creation is quite restricted in that all the services must reside in
 * under Verifone\Core\Service and implementations of for example storage can't be changed without modifying the Factory.
 * Backend and Frontend services also need Configuration object in order to be created.
 */
final class ServiceFactory
{
    const SERVICE_NAMESPACE = '\Verifone\Core\Service\\';
    const RESPONSE_CONVERTER_NAMESPACE = '\Verifone\Core\Converter\Response\\';
    const RESPONSE_CONVERTER_SUFFIX = 'ResponseConverter';
    const RESPONSE_SERVICE_NAME = 'FrontendResponse\FrontendResponseServiceImpl';

    /**
     * Creates a service from name and Configuration.
     *
     * Creates either a frontend or a backend service. The service must exist in namespace Verifone\Core\Service\...
     * Frontend services are called with Frontend\ServiceName and a configuration object corresponding to service.
     * Backend services are called with Backend\ServiceName and a configuration object corresponding to service.
     * It is checked that if Frontend namespace is used, the configuration is of type FrontendConfiguration and
     * correspondingly if Backend namespace is used, that the configuration is of type BackendConfiguration.
     * Used implementations of storage and cryptowrapper are written in Factory and can't be changed without altering
     * the Factory code.
     * For backend services, ResponseConverters corresponding to the service name are created and inserted.
     * @param Configuration $config containing the configuration information for created service.
     * Either backend, frontend or sometimes service specific configuration is needed.
     * @param string $serviceName the name of the service that will be created
     * @return Service that was created
     * @throws ServiceCreationFailedException if for some reason failed to create service, some of the class names used
     * for creation didn't exist, tried to summon a Frontend\... service with something other than FrontendConfiguration
     * etc.
     */
    public static function createService(Configuration $config, $serviceName)
    {
        $storage = self::createStorage();
        $scope = self::validateConfig($serviceName, $config);
        $className = self::getAndValidateClassName(self::SERVICE_NAMESPACE, $serviceName);
        $cryptography = self::createCrypto();
        if ($scope === 'Backend') {
            $responseConverter = self::createResponseConverter($serviceName);
            return new $className($storage, $config, $cryptography, $responseConverter);
        }
        return new $className($storage, $config, $cryptography);
    }

    /**
     * Will return a service for Frontend Response
     *
     * Will return a service for Frontend Response. This service is needed to execute to validate the response
     * and get out general response objects instead of verifone field => value mappings.
     * @param $response array of response fields from verifone
     * @return Service Response service
     * @throws ServiceCreationFailedException if one of the classes instantiated does not exist.
     */
    public static function createResponseService(array $response)
    {
        $storage = self::createStorage();
        $className = self::getAndValidateClassName(self::SERVICE_NAMESPACE, self::RESPONSE_SERVICE_NAME);
        return new $className($storage, $response);
    }

    /**
     * Creates the response converter corresponding to given service name
     *
     * Response converter class name is got by taking Frontend\ or Backend\ off from the start of the service name
     * and also deleting "Service" from the end of the service name. After that a response converter suffix is added
     * to a resulting string and the resulting class name is validated and instantiated.
     * Response converter is needed to convert verifone response in executor to a more generalized form.
     * @param string $serviceName of the service the converter is needed for
     * @return CoreResponseConverter
     * @throws ServiceCreationFailedException if the response converter of deciphered name did not exist
     */
    private static function createResponseConverter($serviceName)
    {
        $name = substr($serviceName, strrpos($serviceName, '\\') +1);
        $name = substr($name, 0, strrpos($name, 'Service'));
        $name = $name . self::RESPONSE_CONVERTER_SUFFIX;
        $className = self::getAndValidateClassName(self::RESPONSE_CONVERTER_NAMESPACE, $name);
        return new $className();
    }

    /**
     * Creates a storage implementation for the service
     * @return ArrayStorage
     */
    private static function createStorage()
    {
        $config = new FieldConfigImpl();
        $storageConfig = $config->getConfig();
        return new ArrayStorage($storageConfig);
    }

    /**
     * Validates the configuration object of the backend or frontend service
     *
     * Validates that backend service has backend configuration or that frontend service has frontend configuration.
     * @param string $className of the service
     * @param Configuration $config to be validated
     * @throws ServiceCreationFailedException if the check failed.
     */
    private static function validateConfig($className, Configuration $config)
    {
        $scope = substr($className, 0, strrpos($className, '\\'));
        if (($scope === 'Frontend' && $config instanceof FrontendConfiguration)
            || ($scope === 'Backend' && $config instanceof BackendConfiguration)) {
            return $scope;
        }
        throw new ServiceCreationFailedException('Scope should be Frontend or Backend, but was ' . $scope);
    }

    /**
     * Gets the full class name from namespace and class name. Validates that the class with given namespace and name
     * exists.
     * @param string $namespace of the class
     * @param string $serviceName of the class
     * @return string full class name
     * @throws ServiceCreationFailedException if given class does not exist
     */
    private static function getAndValidateClassName($namespace, $serviceName)
    {
        $className = $namespace . $serviceName;
        if (!class_exists($className)) {
            throw new ServiceCreationFailedException('Given class ' . $className . ' does not exist');
        }
        return $className;
    }

    /**
     * Creates the cryptography
     * @return CryptUtilImpl
     */
    private static function createCrypto()
    {
        $cryptography = new SeclibCryptography();
        return new CryptUtilImpl($cryptography);
    }
}
