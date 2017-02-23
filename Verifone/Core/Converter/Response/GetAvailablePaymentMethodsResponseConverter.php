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

use Verifone\Core\DependencyInjection\CoreResponse\PaymentMethodImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class GetAvailablePaymentMethodsResponseConverter extends CoreResponseConverter
{
    const CODE = 's-t-1-30_payment-method-code-';
    const TYPE = 's-t-1-30_payment-method-type-';

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = array();
        foreach($originalFields as $integrationField => $integrationValue) {
            if (strpos($integrationField, self::CODE) !== false) {
                $number = substr($integrationField, strrpos($integrationField, '-') + 1);
                $type = $originalFields[self::TYPE . $number];
                $content[] = new PaymentMethodImpl($integrationValue, $type);
            }
        }
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
