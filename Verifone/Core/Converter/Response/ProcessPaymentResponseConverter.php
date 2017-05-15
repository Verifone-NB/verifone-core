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

use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\DependencyInjection\CoreResponse\CardImpl;
use Verifone\Core\DependencyInjection\CoreResponse\PaymentResponseImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class ProcessPaymentResponseConverter extends CoreResponseConverter
{
    public function convert(TransportationResponse $response)
    {
        $fields = $response->getBody();
        $content = new PaymentResponseImpl(
            (isset($fields[FieldConfigImpl::ORDER_NUMBER]) ? $fields[FieldConfigImpl::ORDER_NUMBER] : ''),
            (isset($fields[FieldConfigImpl::CONFIG_TRANSACTION]) ? $fields[FieldConfigImpl::CONFIG_TRANSACTION] : ''),
            (isset($fields[FieldConfigImpl::ORDER_TOTAL_INCL_TAX]) ? $fields[FieldConfigImpl::ORDER_TOTAL_INCL_TAX] : ''),
            (isset($fields[FieldConfigImpl::PAYMENT_METHOD]) ? $fields[FieldConfigImpl::PAYMENT_METHOD] : ''),
            (isset($fields[FieldConfigImpl::RESPONSE_CANCEL_REASON]) ? $fields[FieldConfigImpl::RESPONSE_CANCEL_REASON] : ''),
            new CardImpl(
                '', '', '', '',
                (isset($fields[FieldConfigImpl::PAYMENT_PAN_FIRST_6]) ? $fields[FieldConfigImpl::PAYMENT_PAN_FIRST_6] : ''),
                (isset($fields[FieldConfigImpl::PAYMENT_PAN_LAST_2]) ? $fields[FieldConfigImpl::PAYMENT_PAN_LAST_2] : '')
            )
        );
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
