<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Transport;

use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationWrapper;
use Verifone\Core\Exception\TransportationFailedException;

/**
 * Class TransportImpl
 * @package Verifone\Core\Transport
 * The purpose of this class is to set default validation, configuration etc around the actual transportation
 * wrapper that is used for transporting stuff.
 */
final class TransportImpl implements Transport
{
    private $transport;

    /**
     * TransportImpl constructor.
     * @param TransportationWrapper $transport
     * Sets the default settings, timeout 30, max redirects 0, connection close and content type
     * application/x-www-form-urlencoded.
     */
    public function __construct(TransportationWrapper $transport)
    {
        $this->transport = $transport;
        $this->transport->setTimeout(30);
        $this->transport->setMaxRedirects(0);
        $this->transport->addHeader('Content-type', 'application/x-www-form-urlencoded');
        $this->transport->addHeader('Connection', 'close');
    }

    /**
     * @param array $config of options to change, should be in mode key => value.
     * @return bool whether the change succeeded
     * A possibility to change transportation options.
     */
    public function changeDefaultConfiguration(array $config)
    {
        foreach ($config as $key => $value) {
            $this->transport->setOption($key, $value);
        }
        return true;
    }

    /**
     * Post given data to given destination
     * @param string $destination to send the data to
     * @param mixed $data to be sent
     * @return TransportationResponse containing response
     * @throws TransportationFailedException if transportation fails.
     */
    public function post($destination, $data)
    {
        $response = $this->transport->post($destination, $data);
        if ($response === false || $response->getStatusCode() !== 200) {
            throw new TransportationFailedException($destination, $data);
        }
        return $response;
    }

    /**
     * Get from given destination.
     * @param string $destination
     * @return TransportationResponse containing response
     * @throws TransportationFailedException if transportation fails.
     */
    public function get($destination)
    {
        $response = $this->transport->get($destination);
        if ($response === false || $response->getStatusCode() !== 200) {
            throw new TransportationFailedException($destination, '');
        }
        return $response;
    }

    /**
     * Closes the given transportation if needed.
     */
    public function close()
    {
        $this->transport->close();
    }
}
