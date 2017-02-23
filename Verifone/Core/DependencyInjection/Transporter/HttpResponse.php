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

use Lamia\Validation\Exception\FieldValidationFailedException;

class HttpResponse implements TransportationResponse
{
    private $statusCode;
    private $data;

    public function __construct($statusCode, $data)
    {
        if (is_string($data) === false || strpos($data, "\r\n\r\n") === false) {
            throw new FieldValidationFailedException('httpResponse', 'tried to construct a http response with invalid data');
        }
        
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getBody()
    {
        list($header, $body) = explode("\r\n\r\n", $this->data, 2);
        return $body;
    }

    public function getHeader()
    {
        list($header, $body) = explode("\r\n\r\n", $this->data, 2);
        return $header;
    }

    public function getFull()
    {
        return $this->data;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
