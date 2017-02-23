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


use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\CoreResponse\PaymentResponseImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class FrontendServiceResponseConverter extends CoreResponseConverter
{
    public function convert(TransportationResponse $response)
    {
        $fields = $response->getBody();
        $content = new PaymentResponseImpl(
            (isset($fields[FieldConfig::ORDER_NUMBER]) ? $fields[FieldConfig::ORDER_NUMBER] : ''),
            (isset($fields[FieldConfig::CONFIG_TRANSACTION]) ? $fields[FieldConfig::CONFIG_TRANSACTION] : ''),
            (isset($fields[FieldConfig::ORDER_TOTAL_INCL_TAX]) ? $fields[FieldConfig::ORDER_TOTAL_INCL_TAX] : ''),
            (isset($fields[FieldConfig::PAYMENT_METHOD]) ? $fields[FieldConfig::PAYMENT_METHOD] : ''),
            (isset($fields[FieldConfig::RESPONSE_CANCEL_REASON]) ? $fields[FieldConfig::RESPONSE_CANCEL_REASON] : '')
        );
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
