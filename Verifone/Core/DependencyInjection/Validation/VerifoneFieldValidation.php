<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Validation;


use Lamia\Validation\Validation\DefaultValidation;

class VerifoneFieldValidation extends DefaultValidation
{
    protected function getConfigKey($name)
    {
        return substr($name, 0, strrpos($name, '-') + 1);
    }
}
