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
 * Class CurlTransport
 * @package Verifone\Core\Transport
 */
final class TransportImpl implements Transport
{
    private $transport;

    /**
     * CurlTransport constructor.
     * @param TransportationWrapper $transport
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
     * @param $config array of options to change
     * @return bool whether the change succeeded
     */
    public function changeDefaultConfiguration($config)
    {
        if (!is_array($config)) {
            return false;
        }
        foreach ($config as $key => $value) {
            $this->transport->setOption($key, $value);
        }
        return true;
    }

    /**
     * @param $url string to send the data to
     * @param $data mixed to be sent
     * @return TransportationResponse containing response
     * @throws TransportationFailedException if transportation fails.
     */
    public function request($url, $data)
    {
        $response = $this->transport->post($url, $data);
        if ($response === false || $response->getStatusCode() !== 200) {
            throw new TransportationFailedException($url, $data);
        }
        return $response;
    }

    public function close()
    {
        $this->transport->close();
    }
}
