<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Converter\Request;

use Verifone\Core\Converter\Request\ArrayConverter;

/**
 * Class ArrayConverterTest
 * @package Verifone\Core\Tests\Converter\Request
 * @codeCoverageIgnore
 */
class ArrayConverterTest extends \AbstractConverterTest
{
    public function setUp()
    {
        $this->converter = new ArrayConverter();
        parent::setUp();
    }
    
    /**
     * @param $fields
     * @param $expectedResult
     * @param $action
     * @dataProvider providerTestConvert
     */
    public function testConvert($fields, $expectedResult, $action)
    {
        $this->mockStorage->expects($this->exactly(2))
            ->method('getAsArray')
            ->will($this->returnValue($fields));

        $result = $this->converter->convert($this->mockStorage, $action);

        $this->assertEquals($expectedResult, $result);
    }

    public function providerTestConvert()
    {
        return array(
            array(array(), array(), ''),
            array(array('field1' => 'value1'), array('field1' => 'value1'), 'aa'),
            array(
                array('field1' => 'value1', 'field2' => 'value2'),
                array('field1' => 'value1', 'field2' => 'value2'),
                'POST'
            )
        );
    }
}
