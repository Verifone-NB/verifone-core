<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Converter\Response;


use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\Converter\Response\FrontendServiceResponseConverter;
use Verifone\Core\DependencyInjection\Transporter\CoreResponse;

class FrontendServiceResponseConverterImpl extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $converter = new FrontendServiceResponseConverter();
        $data = new CoreResponse(0, array(
            FieldConfigImpl::ORDER_NUMBER => 123,
            FieldConfigImpl::CONFIG_TRANSACTION => 12345,
            FieldConfigImpl::ORDER_TOTAL_INCL_TAX => 12,
            FieldConfigImpl::PAYMENT_METHOD => 'asdf',
            FieldConfigImpl::RESPONSE_CANCEL_REASON => 'test test test'
        ));
        $response = $converter->convert($data);
        $responseData = $response->getBody();
        $this->assertEquals($responseData->getOrderNumber(), 123);
        $this->assertEquals($responseData->getTransactionNumber(), 12345);
        $this->assertEquals($responseData->getOrderGrossAmount(), 12);
        $this->assertEquals($responseData->getPaymentMethodCode(), 'asdf');
        $this->assertEquals($responseData->getCancelMessage(), 'test test test');
    }
}
