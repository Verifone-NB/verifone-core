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
use Verifone\Core\Exception\ResponseCheckFailedException;

class BackendResponseValidation implements ResponseValidation
{
    private $mandatoryResponseFields = array(
        FieldConfigImpl::RESPONSE_ID
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
     * @param array $matchingFields
     * @throws ResponseCheckFailedException if something went wrong
     */
    public function validate($requestFields, $responseFields, $publicKey, $matchingFields = array())
    {
        $this->utils->checkErrorMessage($responseFields);
        $this->utils->fieldsExist($responseFields, $this->mandatoryResponseFields);
        $this->utils->matchesAll($requestFields, $responseFields, $matchingFields);
        $this->utils->matches(
            $responseFields[FieldConfigImpl::REQUEST_ID],
            $responseFields[FieldConfigImpl::RESPONSE_ID],
            FieldConfigImpl::REQUEST_ID,
            FieldConfigImpl::RESPONSE_ID
        );
        $this->utils->verifySignature($responseFields, $publicKey);
    }
}
