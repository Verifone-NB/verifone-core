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

use Verifone\Core\DependencyInjection\Transporter\CoreResponse;
use Verifone\Core\DependencyInjection\Transporter\TransportationResponse;

class RemoveSavedCreditCardsResponseConverter extends CoreResponseConverter
{
    const REMOVED_FIELD = 'l-t-1-10_removed-count';

    public function convert(TransportationResponse $response)
    {
        $originalFields = $response->getBody();
        $content = boolval($originalFields[self::REMOVED_FIELD]);
        return new CoreResponse(CoreResponseConverter::STATUS_OK, $content);
    }
}
