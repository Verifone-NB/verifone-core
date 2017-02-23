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

class CoreResponse implements TransportationResponse
{
    private $statusCode;
    private $content;

    public function __construct($statusCode, $data)
    {
        $this->statusCode = $statusCode;
        $this->content = $data;
    }

    public function getBody()
    {
        return $this->content;
    }

    public function getFull()
    {
        return $this->content;
    }

    public function getHeader()
    {
        return $this->content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
