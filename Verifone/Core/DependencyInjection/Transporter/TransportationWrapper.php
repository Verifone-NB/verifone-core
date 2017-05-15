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
 * Interface TransportationWrapper for transporting data
 * @package Verifone\Core\DependencyInjection\Transporter
 */
interface TransportationWrapper
{
    /**
     * TransportationWrapper constructor.
     */
    public function __construct();

    /**
     * destructor if something needs to be done
     */
    public function __destruct();

    /**
     * Post data to given url
     * @param $url string url to post data to
     * @param $data mixed to be posted
     * @return mixed response or false if not successful
     */
    public function post($url, $data);

    /**
     * Get to given url
     * @param $url
     * @return mixed
     */
    public function get($url);

    /**
     * Set option to transportation
     * @param $option string option name
     * @param $value mixed option value
     * @return mixed
     */
    public function setOption($option, $value);

    /**
     * Add header to transportation
     * @param $key string header name
     * @param $value mixed header value
     * @return mixed
     */
    public function addHeader($key, $value);

    /**
     * Close transportation
     * @return mixed
     */
    public function close();
    
    public function setTimeout($timeout);
    
    public function setMaxRedirects($maxRedirects);
}
