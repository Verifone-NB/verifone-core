<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Converter\Request;

use Verifone\Core\Storage\Storage;

/**
 * Interface RequestConverterInterface
 * @package Verifone\Core\Converter\Request
 * 
 * Converts requests to the needed form
 */
interface RequestConverter
{
    /**
     * Takes a ServiceInterface and converts it's fields into desired form
     * @param Storage $storage containing the fields
     * @param action
     * @return string fields in converted form
     */
    public function convert(Storage $storage, $action);
}
