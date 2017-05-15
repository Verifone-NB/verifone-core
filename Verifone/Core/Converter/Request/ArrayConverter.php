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

use Verifone\Core\Exception\UnableToConvertFieldsException;
use Verifone\Core\Storage\Storage;

/**
 * Class ArrayConverter
 * @package Verifone\Core\Converter\Request
 * Converts storage to an array.
 * 
 * Takes in Storage containing verifone fields and returns them as an array.
 */
class ArrayConverter implements RequestConverter
{
    const FIELD_ACTION = 'action';
    /**
     * Converts the fields in StorageInterface into array form and returns it
     * @param Storage $storage containing fields
     * @param string $action
     * @return array of fields
     * @throws UnableToConvertFieldsException if can't convert fields from given parameter
     */
    public function convert(Storage $storage, $action)
    {
        if (!is_array($storage->getAsArray())) {
            throw new UnableToConvertFieldsException();
        }
        $fields = $storage->getAsArray();
        $fields[self::FIELD_ACTION] = $action;
        return $fields;
    }
}
