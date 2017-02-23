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

use Lamia\HttpClient\HttpClient;

class LamiaHttpClientWrapper implements TransportationWrapper
{
    private $client;

    public function __construct()
    {
        $this->client = new HttpClient('');
    }

    public function addHeader($key, $value)
    {
        $this->client->setHeaders(array($key => $value));
    }

    public function post($url, $data)
    {
        $data = $this->prepareData($data);
        try {
            $response = $this->client->post($url, $data);
            $statusCode = $response->getStatusCode();
            if (in_array($statusCode, $this->getOkResponseCodes()) !== false) {
                $httpResponse = new HttpResponse(
                    $statusCode, json_encode($response->getHeaders()) . "\r\n\r\n" . $response->getBody()
                );
                return $httpResponse;
            }
        } catch (\Exception $e) {
        }
        return false;
    }

    public function setMaxRedirects($maxRedirects)
    {
        $this->setOption(CURLOPT_MAXREDIRS, $maxRedirects);
    }

    public function setOption($option, $value)
    {
        $this->client->setOption($option, $value);
    }

    public function setTimeout($timeout)
    {
        $this->setOption(CURLOPT_TIMEOUT, $timeout);
    }

    public function close()
    {
        // do nothing
    }

    public function __destruct()
    {
        // do nothing
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
}
