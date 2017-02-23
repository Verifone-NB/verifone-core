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

use Verifone\Core\DependencyInjection\CoreResponse\PaymentStatusImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class GetPaymentStatusResponseConverter extends CoreResponseConverter
{
    const STATUS_CODE = 's-f-1-30_payment-status-code';

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = new PaymentStatusImpl($originalFields[self::STATUS_CODE]);
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
