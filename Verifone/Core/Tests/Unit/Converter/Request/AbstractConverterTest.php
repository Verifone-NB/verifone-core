<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

abstract class AbstractConverterTest extends \Verifone\Core\Tests\Unit\VerifoneTest
{
    protected $mockStorage;
    protected $converter;

    public function setUp(): void
    {
        $this->mockStorage = $this->getMockBuilder('\Verifone\Core\Storage\Storage')
            ->getMock();
    }

    /**
     * @param $fields
     *
     * @dataProvider providerTestConvertNonArrayGetFields
     */
    public function testConvertNonArrayGetFields($fields)
    {
        $this->expectException(\Verifone\Core\Exception\UnableToConvertFieldsException::class);
        $this->mockStorage->expects($this->exactly(1))
            ->method('getAsArray')
            ->will($this->returnValue($fields));
        $this->converter->convert($this->mockStorage, '');
    }

    public function providerTestConvertNonArrayGetFields()
    {
        return array(
            array(null),
            array(''),
            array(123),
            array(true),
            array(false),
        );
    }
}
