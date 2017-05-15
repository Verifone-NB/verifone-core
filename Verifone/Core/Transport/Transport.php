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

/**
 * Interface Transport
 * @package Verifone\Core\Transport
 * The purpose of this class is to be a project specific interface for all the project transportation needs.
 * Default settings etc should be set in the implementation so that the classes that use transportation don't 
 * ideally have to think this beyond calling post or get when needed.
 */
interface Transport
{
    /**
     * Transport constructor.
     * @param TransportationWrapper $transport injected wrapper for the transport mode implementation
     */
    public function __construct(TransportationWrapper $transport);

    /**
     * Change the default configuration of transport
     * @param array $config of configurations to change
     * @return bool true if succeeded, false if not
     */
    public function changeDefaultConfiguration(array $config);

    /**
     * Post data to given destination
     * @param string $destination to send data to
     * @param mixed $data to send to destination
     * @return TransportationResponse containing response
     */
    public function post($destination, $data);

    /**
     * Get data from given destination
     * @param string $destination to get the data from
     * @return TransportationResponse containing response
     */
    public function get($destination);

    /**
     * close the transportation if needed.
     */
    public function close();
}
