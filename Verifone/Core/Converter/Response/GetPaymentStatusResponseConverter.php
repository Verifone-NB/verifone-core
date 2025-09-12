<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 * @author     Szymon Nosal <simon@lamia.fi>
 */

namespace Verifone\Core\Converter\Response;

use Verifone\Core\DependencyInjection\CoreResponse\PaymentStatusImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class GetPaymentStatusResponseConverter extends CoreResponseConverter
{
    const STATUS_CODE = 's-f-1-30_payment-status-code';
    const ORDER_AMOUNT = 'l-f-1-20_order-gross-amount';
    const TRANSACTION_NUMBER = 'l-f-1-20_transaction-number';
    const PAYMENT_METHOD = 's-f-1-30_payment-method-code';
    const ORDER_NUMBER = 's-f-1-36_order-number';
    const ORDER_TIMESTAMP = 't-f-14-19_order-timestamp';

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = new PaymentStatusImpl(
            $originalFields[self::STATUS_CODE],
            $originalFields[self::ORDER_AMOUNT],
            $originalFields[self::TRANSACTION_NUMBER],
            $originalFields[self::PAYMENT_METHOD],
            $originalFields[self::ORDER_NUMBER]
        );
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
