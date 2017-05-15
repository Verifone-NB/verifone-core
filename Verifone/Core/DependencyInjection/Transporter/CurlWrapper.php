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

use \Exception;

/**
 * Class CurlWrapperImpl
 * @package Verifone\Core\DependencyInjection\Transporter
 */
class CurlWrapper implements TransportationWrapper
{
    const USER_AGENT = 'Verifone_Core_Curl';
    private $curl;
    private $headers;

    /**
     * CurlWrapperImpl constructor.
     * initiates curl
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new Exception('cURL not loaded'); //@codeCoverageIgnore
        }
        $this->curl = curl_init();
        $this->setOption(CURLOPT_USERAGENT, self::USER_AGENT);
        $this->setOption(CURLOPT_HEADER, true);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * closes curl
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Sending curl post request to url with data as payload
     * @param $url string to sent the request to
     * @param $data mixed to be sent
     * @return TransportationResponse|bool post result or false if not successful
     */
    public function post($url, $data)
    {
        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_POST, true);
        $data = $this->prepareData($data);
        $this->setOption(CURLOPT_POSTFIELDS, $data);
        return $this->execRequest();
    }

    public function get($url)
    {
        $this->setOption(CURLOPT_URL, $url);
        return $this->execRequest();
    }

    /**
     * close curl
     */
    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * set curl option
     * @param $option string curl option name
     * @param $value string the value to be inserted into curl option
     * @return mixed
     */
    public function setOption($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * add http header to curl options
     * @param string $key header name
     * @param mixed $value header value
     * @return mixed
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $key . ': ' . $value;
        $this->setOption(CURLOPT_HTTPHEADER, array_values($this->headers));
    }

    /**
     * Send request and return response
     * @return TransportationResponse|bool response if succeeds (http status code 20*), false otherwise
     */
    private function execRequest()
    {
        $response = curl_exec($this->curl);
        if ($response !== false) {
            $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
            if (in_array($statusCode, $this->getOkResponseCodes()) !== false) {
                $httpResponse = new HttpResponse($statusCode, $response);
                return $httpResponse;
            }
        }
        return false;
    }

    /**
     * If data is array or object, transform it to correct form
     * @param $data
     * @return mixed data
     */
    private function prepareData($data)
    {
        if (is_object($data) || is_array($data)) {
            return http_build_query($data);
        }
        return $data;
    }

    /**
     * @return array of successful http status codes
     */
    private function getOkResponseCodes()
    {
        return array(200, 201, 202, 203, 204, 205, 206);
    }
    
    public function setMaxRedirects($maxRedirects)
    {
        $this->setOption(CURLOPT_MAXREDIRS, $maxRedirects);
    }
    
    public function setTimeout($timeout)
    {
        $this->setOption(CURLOPT_TIMEOUT, $timeout);
    }
}
