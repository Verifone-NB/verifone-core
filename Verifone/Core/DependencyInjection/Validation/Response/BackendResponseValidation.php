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
use Verifone\Core\Exception\ResponseCheckFailedException;

class BackendResponseValidation implements ResponseValidation
{
    const OPERATION_ERROR_LABEL = 'operation';
    const REQUEST_ID_ERROR_LABEL = 'request-id';
    const RESPONSE_ID_ERROR_LABEL = 'response-id';

    private $mandatoryRequestFields = array(
        FieldConfig::OPERATION,
        FieldConfig::REQUEST_ID
    );
    
    private $mandatoryResponseFields = array(
        FieldConfig::OPERATION,
        FieldConfig::REQUEST_ID,
        FieldConfig::RESPONSE_ID
    );
    
    private $utils;

    public function __construct(ResponseValidationUtils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * Validate that response is not erroneous
     * @param array $responseFields
     * @param array $requestFields
     * @param string $publicKey to verify response signature
     * @throws ResponseCheckFailedException if something went wrong
     */
    public function validate($requestFields, $responseFields, $publicKey)
    {
        $this->utils->fieldsExist($requestFields, $this->mandatoryRequestFields);
        $this->utils->fieldsExist($responseFields, $this->mandatoryResponseFields);

        $this->utils->matches(
            $requestFields[FieldConfig::OPERATION],
            $responseFields[FieldConfig::OPERATION],
            self::OPERATION_ERROR_LABEL
        );
        $this->utils->matches(
            $requestFields[FieldConfig::REQUEST_ID],
            $responseFields[FieldConfig::REQUEST_ID],
            self::REQUEST_ID_ERROR_LABEL
        );
        $this->utils->matches(
            $responseFields[FieldConfig::REQUEST_ID],
            $responseFields[FieldConfig::RESPONSE_ID],
            self::RESPONSE_ID_ERROR_LABEL,
            self::REQUEST_ID_ERROR_LABEL
        );
        $this->utils->checkErrorMessage($responseFields);
        $this->utils->verifySignature($responseFields, $publicKey);
    }
}
