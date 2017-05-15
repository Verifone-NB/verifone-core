<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Configuration;

/**
 * Interface FieldConfig
 * @package Verifone\Core\Configuration
 * The purpose of this class is to return the configuration of the verifone fields
 */
interface FieldConfig
{
    /**
     * @return array of verifone field configuration
     */
    public function getConfig();
}
