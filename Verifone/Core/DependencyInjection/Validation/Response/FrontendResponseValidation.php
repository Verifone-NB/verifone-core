<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Validation\Response;


use Verifone\Core\Configuration\FieldConfig;

class FrontendResponseValidation implements ResponseValidation
{
    const ORDER_NUMBER_ERROR_LABEL = 'order number';
    const TIMESTAMP_ERROR_LABEL = 'timestamp';
    const GROSS_AMOUNT_ERROR_LABEL = 'gross amount';
    const CURRENCY_ERROR_LABEL = 'currency code';

    private $mandatorySuccessResponseFields = array(
        FieldConfig::ORDER_NUMBER,
        FieldConfig::ORDER_TIMESTAMP,
        FieldConfig::ORDER_TOTAL_INCL_TAX,
        FieldConfig::ORDER_CURRENCY,
        FieldConfig::SIGNATURE_ONE,
        FieldConfig::SIGNATURE_TWO,
        FieldConfig::INTERFACE_VERSION,
        FieldConfig::CONFIG_SOFTWARE_VERSION,
        FieldConfig::CONFIG_TRANSACTION,
        FieldConfig::PAYMENT_METHOD
    );

    private $mandatoryCancelResponseFields = array(
        FieldConfig::ORDER_NUMBER,
        FieldConfig::SIGNATURE_ONE,
        FieldConfig::SIGNATURE_TWO,
        FieldConfig::INTERFACE_VERSION,
        FieldConfig::CONFIG_SOFTWARE_VERSION
    );
    
    private $mandatoryCancelRequestFields = array(
        FieldConfig::ORDER_NUMBER
    );

    private $mandatorySuccessRequestFields = array(
        FieldConfig::ORDER_NUMBER,
        FieldConfig::ORDER_TIMESTAMP,
        FieldConfig::ORDER_TOTAL_INCL_TAX,
        FieldConfig::ORDER_CURRENCY,
    );

    private $utils;
    private $requestFields;
    private $responseFields;

    public function __construct(ResponseValidationUtils $utils)
    {
        $this->utils = $utils;
    }

    public function validate($requestFields, $responseFields, $publicKey)
    {
        $this->requestFields = $requestFields;
        $this->responseFields = $responseFields;

        if ($this->isCancelResponse()) {
            $this->validateCancelResponse();
        }
        else {
            $this->validateSuccessResponse();
        }

        $this->validateGeneral($publicKey);
    }

    private function isCancelResponse()
    {
        return isset($this->responseFields[FieldConfig::RESPONSE_CANCEL_REASON]);
    }

    private function validateCancelResponse()
    {
        $this->utils->fieldsExist($this->responseFields, $this->mandatoryCancelResponseFields);
        $this->utils->fieldsExist($this->requestFields, $this->mandatoryCancelRequestFields);
    }

    private function validateSuccessResponse()
    {
        $this->utils->fieldsExist($this->requestFields, $this->mandatorySuccessRequestFields);
        $this->utils->fieldsExist($this->responseFields, $this->mandatorySuccessResponseFields);
        $this->utils->matches(
            $this->responseFields[FieldConfig::ORDER_TIMESTAMP],
            $this->requestFields[FieldConfig::ORDER_TIMESTAMP],
            self::TIMESTAMP_ERROR_LABEL
        );
        $this->utils->matches(
            $this->responseFields[FieldConfig::ORDER_TOTAL_INCL_TAX],
            $this->requestFields[FieldConfig::ORDER_TOTAL_INCL_TAX],
            self::GROSS_AMOUNT_ERROR_LABEL
        );
        $this->utils->matches(
            $this->responseFields[FieldConfig::ORDER_CURRENCY],
            $this->requestFields[FieldConfig::ORDER_CURRENCY],
            self::CURRENCY_ERROR_LABEL
        );
    }

    private function validateGeneral($publicKey)
    {
        $this->utils->matches(
            $this->responseFields[FieldConfig::ORDER_NUMBER],
            $this->requestFields[FieldConfig::ORDER_NUMBER],
            self::ORDER_NUMBER_ERROR_LABEL
        );
        $this->utils->verifySignature($this->responseFields, $publicKey);
    }
}
