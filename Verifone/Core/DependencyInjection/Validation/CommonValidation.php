<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

/**
 * Created by PhpStorm.
 * User: irina
 * Date: 21.2.2017
 * Time: 19:14
 */

namespace Verifone\Core\DependencyInjection\Validation;


interface CommonValidation
{
    public function validateResponse(
        array $requestFields,
        array $responseFields,
        $publicKey,
        array $matchingFieldNames = array()
    );

    public function validate(array $requestFields);
}
