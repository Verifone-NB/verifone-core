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

use Verifone\Core\DependencyInjection\Service\TransactionImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class ListTransactionNumbersResponseConverter extends CoreResponseConverter
{
    const CODE = 's-f-1-30_payment-method-code-';
    const NUMBER = 'l-f-1-20_transaction-number-';

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = array();
        foreach($originalFields as $integrationField => $integrationValue) {
            if (strpos($integrationField, self::CODE) !== false) {
                $number = substr($integrationField, strrpos($integrationField, '-') + 1);
                $transactionNumber = $originalFields[self::NUMBER . $number];
                $content[] = new TransactionImpl($integrationValue, $transactionNumber);
            }
        }
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
