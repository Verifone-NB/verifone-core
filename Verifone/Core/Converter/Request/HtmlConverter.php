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
 * Class HtmlConverter
 * @package Verifone\Core\Converter\Request
 */
final class HtmlConverter implements RequestConverter
{
    /**
     * Converts the fields in StorageInterface into a html form and returns it
     * @param Storage $storage containing fields
     * @return array fields as a html form string
     * @throws UnableToConvertFieldsException if can't convert fields from given parameter
     */
    public function convert(Storage $storage, $action)
    {
        if (!is_array($storage->getAsArray())) {
            throw new UnableToConvertFieldsException();
        }

        $result = $this->getFormHeader($action);
        $result .= $this->convertFields($storage->getAsArray());
        $result .= $this->getFormEnd();
        return $result;
    }

    /**
     * @param $action string for form action tag
     * @return string form head
     */
    private function getFormHeader($action)
    {
        $result = '<form method="POST" id="verifone_form" action="' . $action . '" target="_parent">' . "\n";
        return $result . 'Redirecting to VerifonePayment.' . "\n";
    }

    /**
     * @param $fields array to be converted
     * @return string of fields as hidden input lines
     */
    private function convertFields($fields)
    {
        $result = '';
        foreach ($fields as $key => $value) {
            $result .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
        }
        return $result;
    }

    /**
     * @return string the end of the form
     */
    private function getFormEnd()
    {
        $result = '<br>' . "\n";
        $result .= '<script type="text/javascript">document.getElementById("verifone_form").submit();</script>' . "\n";
        return $result . '</form>';
    }
}
