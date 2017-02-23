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


use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfiguration;
use Verifone\Core\DependencyInjection\Configuration\Configuration;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtilImpl;
use Verifone\Core\DependencyInjection\CryptUtils\SeclibCryptography;
use Verifone\Core\Exception\ServiceCreationFailedException;
use Verifone\Core\Service\AbstractService;
use Verifone\Core\Service\Service;
use Verifone\Core\Storage\ArrayStorage;

final class ServiceFactory
{
    const SERVICE_NAMESPACE = '\Verifone\Core\Service\\';
    const RESPONSE_CONVERTER_NAMESPACE = '\Verifone\Core\Converter\Response\\';
    const RESPONSE_CONVERTER_SUFFIX = 'ResponseConverter';
    const RESPONSE_SERVICE_NAME = 'FrontendResponse\FrontendResponseServiceImpl';

    /**
     * @param Configuration $config
     * @param $serviceName
     * @return Service
     * @throws ServiceCreationFailedException
     */
    public static function createService(Configuration $config, $serviceName)
    {
        $storage = self::createStorage();
        $scope = self::validateConfig($serviceName, $config);
        $className = self::getAndValidateClassName($serviceName);
        $cryptography = self::createCrypto();
        if ($scope === 'Backend') {
            $responseConverter = self::createResponseConverter($serviceName);
            return new $className($storage, $config, $cryptography, $responseConverter);
        }
        return new $className($storage, $config, $cryptography);
    }

    /**
     * Will return a service for Frontend Response
     * @param $response
     * @return Service
     * @throws ServiceCreationFailedException
     */
    public static function createResponseService(array $response)
    {
        $storage = self::createStorage();
        $className = self::getAndValidateClassName(self::RESPONSE_SERVICE_NAME);
        return new $className($storage, $response);
    }

    private static function createResponseConverter($serviceName)
    {
        $name = substr($serviceName, strrpos($serviceName, '\\') +1);
        $name = substr($name, 0, strrpos($name, 'Service'));
        $className = self::RESPONSE_CONVERTER_NAMESPACE . $name . self::RESPONSE_CONVERTER_SUFFIX;
        if (!class_exists($className)) {
            throw new ServiceCreationFailedException('Given response converter class ' . $className . ' does not exist');
        }
        return new $className();
    }

    /**
     * Creates a storage implementation
     * @return ArrayStorage
     */
    private static function createStorage()
    {
        $storageConfig = FieldConfig::getConfig();
        return new ArrayStorage($storageConfig);
    }

    /**
     * Validates the config
     * @param $className
     * @param $config
     * @throws ServiceCreationFailedException
     */
    private static function validateConfig($className, $config)
    {
        $scope = substr($className, 0, strrpos($className, '\\'));
        if (($scope === 'Frontend' && $config instanceof FrontendConfiguration)
            || ($scope === 'Backend' && $config instanceof BackendConfiguration)) {
            return $scope;
        }
        throw new ServiceCreationFailedException('Scope should be Frontend or Backend, but was ' . $scope);
    }

    /**
     * Gets the full class name
     * @param $serviceName
     * @return string
     * @throws ServiceCreationFailedException
     */
    private static function getAndValidateClassName($serviceName)
    {
        $className = self::SERVICE_NAMESPACE . $serviceName;
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
