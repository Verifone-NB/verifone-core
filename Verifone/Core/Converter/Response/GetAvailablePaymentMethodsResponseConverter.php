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
use Verifone\Core\DependencyInjection\CoreResponse\PaymentMethodImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class GetAvailablePaymentMethodsResponseConverter extends CoreResponseConverter
{
    const CODE = FieldConfigImpl::RESPONSE_PAYMENT_METHOD_CODE;
    const TYPE = FieldConfigImpl::RESPONSE_PAYMENT_METHOD_TYPE;
    const MIN_LIMIT = FieldConfigImpl::RESPONSE_PAYMENT_METHOD_MIN;
    const MAX_LIMIT = FieldConfigImpl::RESPONSE_PAYMENT_METHOD_MAX;

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = array();
        foreach($originalFields as $integrationField => $integrationValue) {
            if (strpos($integrationField, self::CODE) !== false) {
                $number = substr($integrationField, strrpos($integrationField, '-') + 1);
                $type = isset($originalFields[self::TYPE . $number]) ? $originalFields[self::TYPE . $number] : '';
                $minLimit =  isset($originalFields[self::MIN_LIMIT . $number]) ? $originalFields[self::MIN_LIMIT . $number] : -1;
                $maxLimit = isset($originalFields[self::MAX_LIMIT . $number]) ? $originalFields[self::MAX_LIMIT . $number] : -1;
                $content[] = new PaymentMethodImpl($integrationValue, $type, $minLimit, $maxLimit);
            }
        }
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
