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


interface ResponseValidationUtils
{
    public function matches($value1, $value2, $fieldName1, $fieldName2 = '');

    public function matchesAll(array $requestFields, array $responseFields, array $matchingFields);
    
    public function verifySignature(array $responseFields, $publicKey);

    public function checkErrorMessage(array $responseFields);

    public function fieldsExist(array $responseFields, array $mandatoryFields);
}
