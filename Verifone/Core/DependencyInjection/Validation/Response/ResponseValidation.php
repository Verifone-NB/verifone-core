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


interface ResponseValidation
{
    public function __construct(ResponseValidationUtils $utils);

    public function validate($requestFields, $responseFields, $publicKey, $matchingFields = array());
}
