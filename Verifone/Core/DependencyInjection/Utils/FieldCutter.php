<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 *
 */
namespace Verifone\Core\DependencyInjection\Utils;

use Verifone\Core\Configuration\FieldConfig;

class FieldCutter implements Cutter
{
    private $fieldConfig;

    public function __construct(FieldConfig $config)
    {
        $this->fieldConfig = $config->getConfig();
    }

    /**
     * @param array $fields
     * @return array of fields
     */
    public function cutFields(array $fields)
    {
        foreach ($fields as $name => $value) {
            if (!isset($this->fieldConfig[$name])) {
                continue;
            }
            $constraints = $this->fieldConfig[$name];
            if ($this->shouldBeCut($constraints)) {
                $fields[$name] =  mb_substr($value, 0, $constraints['max']);
            }
        }
        return $fields;
    }

    private function shouldBeCut($constraints)
    {
        return isset($constraints['cut']) && $constraints['cut'] === true
            && isset($constraints['max']) && is_int($constraints['max']);
    }
}
