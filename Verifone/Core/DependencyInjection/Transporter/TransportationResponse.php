<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Transporter;

/**
 * Interface TransportationResponse
 * Response interface to be used as a result of TransportationWrapper (or other Transportation)
 * @package Verifone\Core\DependencyInjection\Transporter
 */
interface TransportationResponse
{
    /**
     * TransportationResponse constructor.
     * @param mixed $statusCode of response
     * @param mixed $data that is the actual response
     */
    public function __construct($statusCode, $data);

    /**
     * @return mixed
     * get the whole response data
     */
    public function getFull();

    /**
     * @return mixed
     * get the body / data part of the response
     */
    public function getBody();

    /**
     * @return mixed
     * get possible headers of response
     */
    public function getHeader();

    /**
     * @return mixed
     * get response status code
     */
    public function getStatusCode();
}
