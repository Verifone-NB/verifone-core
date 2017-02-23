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
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Exception\ResponseCheckFailedException;

class ResponseValidationUtilsImpl implements ResponseValidationUtils
{
    private $cryptUtil;
    
    public function __construct(CryptUtil $cryptUtil)
    {
        $this->cryptUtil = $cryptUtil;
    }
    
    public function matches($value1, $value2, $fieldName1, $fieldName2 = '')
    {
        if ($value1 !== $value2) {
            $message = sprintf(
                "%s %s does not equal to %s %s",
                (is_string($fieldName1) ? $fieldName1 : json_encode($fieldName1)),
                (is_string($value1) ? $value1 : json_encode($value1)),
                (is_string($fieldName2) ? $fieldName2 : json_encode($fieldName2)),
                (is_string($value2) ? $value2 : json_encode($value2))
            );
            throw new ResponseCheckFailedException($message);
        }
    }
    
    public function verifySignature($responseFields, $publicKey)
    {
        if (!$this->cryptUtil->verifyResponseFieldsSignature($publicKey, $responseFields)) {
            throw new ResponseCheckFailedException('Verifying signature one of response failed');
        }
    }
    
    public function checkErrorMessage($responseFields)
    {
        $errorMessage = FieldConfig::RESPONSE_ERROR_MESSAGE;
        if (isset($responseFields[$errorMessage]) && $responseFields[$errorMessage] !== '') {
            throw new ResponseCheckFailedException(
                'There wes error set in verifone response: ' . $responseFields[$errorMessage]
            );
        }
    }
    
    public function fieldsExist(array $responseFields, array $mandatoryFields)
    {
        foreach ($mandatoryFields as $mandatoryField) {
            if (!isset($responseFields[$mandatoryField])) {
                throw new ResponseCheckFailedException('Compulsory field ' . $mandatoryField . ' was not found in response.');
            }
        }
    }
}
