<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Converter\Response;

use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;


/***
 * Interface ResponseConverterInterface
 * @package Verifone\Core\Converter\Response
 * Converts responses from verifone to desired format
 */
interface ResponseConverter
{
    /**
     * @param $response TransportationResponse be converted
     * @return array response converted into desired format
     * Converts responses from verifone to desired format
     */
    public function convert(TransportationResponse $response);
}
