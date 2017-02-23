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

use Verifone\Core\DependencyInjection\CoreResponse\CardImpl;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class GetSavedCreditCardsResponseConverter extends CoreResponseConverter
{
    const METHOD_CODE = 's-t-1-30_payment-method-code-';
    const METHOD_ID = 'l-t-1-20_payment-method-id-';
    const METHOD_TITLE = 's-t-1-30_payment-method-title-';
    const EXPECTED_VALIDITY = 's-t-1-6_card-expected-validity-';

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = array();
        foreach($originalFields as $integrationField => $integrationValue) {
            if (strpos($integrationField, self::METHOD_CODE) !== false) {
                $number = substr($integrationField, strrpos($integrationField, '-') + 1);
                $id = $originalFields[self::METHOD_ID . $number];
                $title = $originalFields[self::METHOD_TITLE . $number];
                $validity = $originalFields[self::EXPECTED_VALIDITY . $number];
                $content[] = new CardImpl($integrationValue, $id, $title, $validity);
            }
        }
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
