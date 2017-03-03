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


use Verifone\Core\Configuration\FieldConfigImpl;

class FrontendResponseValidation implements ResponseValidation
{
    const ORDER_NUMBER_ERROR_LABEL = 'order number';
    const TIMESTAMP_ERROR_LABEL = 'timestamp';
    const GROSS_AMOUNT_ERROR_LABEL = 'gross amount';
    const CURRENCY_ERROR_LABEL = 'currency code';

    private $mandatorySuccessResponseFields = array(
        FieldConfigImpl::ORDER_NUMBER,
        FieldConfigImpl::ORDER_TIMESTAMP,
        FieldConfigImpl::ORDER_TOTAL_INCL_TAX,
        FieldConfigImpl::ORDER_CURRENCY,
        FieldConfigImpl::SIGNATURE_ONE,
        FieldConfigImpl::SIGNATURE_TWO,
        FieldConfigImpl::INTERFACE_VERSION,
        FieldConfigImpl::CONFIG_SOFTWARE_VERSION,
        FieldConfigImpl::CONFIG_TRANSACTION,
        FieldConfigImpl::PAYMENT_METHOD
    );

    private $mandatoryCancelResponseFields = array(
        FieldConfigImpl::ORDER_NUMBER,
        FieldConfigImpl::SIGNATURE_ONE,
        FieldConfigImpl::SIGNATURE_TWO,
        FieldConfigImpl::INTERFACE_VERSION,
        FieldConfigImpl::CONFIG_SOFTWARE_VERSION
    );
    
    private $mandatoryCancelRequestFields = array(
        FieldConfigImpl::ORDER_NUMBER
    );

    private $mandatorySuccessRequestFields = array(
        FieldConfigImpl::ORDER_NUMBER,
        FieldConfigImpl::ORDER_TIMESTAMP,
        FieldConfigImpl::ORDER_TOTAL_INCL_TAX,
        FieldConfigImpl::ORDER_CURRENCY,
    );

    private $utils;
    private $requestFields;
    private $responseFields;

    public function __construct(ResponseValidationUtils $utils)
    {
        $this->utils = $utils;
    }

    public function validate($requestFields, $responseFields, $publicKey, $matchingFields = array())
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
        return isset($this->responseFields[FieldConfigImpl::RESPONSE_CANCEL_REASON]);
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
            $this->responseFields[FieldConfigImpl::ORDER_TIMESTAMP],
            $this->requestFields[FieldConfigImpl::ORDER_TIMESTAMP],
            self::TIMESTAMP_ERROR_LABEL
        );
        $this->utils->matches(
            $this->responseFields[FieldConfigImpl::ORDER_TOTAL_INCL_TAX],
            $this->requestFields[FieldConfigImpl::ORDER_TOTAL_INCL_TAX],
            self::GROSS_AMOUNT_ERROR_LABEL
        );
        $this->utils->matches(
            $this->responseFields[FieldConfigImpl::ORDER_CURRENCY],
            $this->requestFields[FieldConfigImpl::ORDER_CURRENCY],
            self::CURRENCY_ERROR_LABEL
        );
    }

    private function validateGeneral($publicKey)
    {
        $this->utils->matches(
            $this->responseFields[FieldConfigImpl::ORDER_NUMBER],
            $this->requestFields[FieldConfigImpl::ORDER_NUMBER],
            self::ORDER_NUMBER_ERROR_LABEL
        );
        $this->utils->verifySignature($this->responseFields, $publicKey);
    }
}
