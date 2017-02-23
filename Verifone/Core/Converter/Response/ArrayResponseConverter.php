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

class ArrayResponseConverter implements ResponseConverter
{
    public function convert(TransportationResponse $response)
    {
        $responseBody = $response->getBody();
        if (empty($responseBody)) {
            return array();
        }
        $fields = array();
        $respFields = explode('&', $responseBody);
        foreach ($respFields as $respField) {
            $eq = explode('=', $respField);
            if (isset($eq[0]) && isset($eq[1])) {
                $eq[1] = $this->convertTimestamps($eq[1]);
                $fields[$eq[0]] = $eq[1];
            }
        }
        return $fields;
    }

    private function convertTimestamps($data) {
        if (strpos($data, '%3A') !== false) {
            $data = str_replace('%3A', ':', $data);
            $data = str_replace('+', ' ', $data);
        }
        return $data;
    }
}
