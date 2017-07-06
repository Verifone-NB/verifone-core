<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Exception;


use Verifone\Core\Exception\UnableToConvertFieldsException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class UnableToConvertFieldsExceptionTest
 * @package Verifone\Core\Tests\Exception
 * @codeCoverageIgnore
 */
class UnableToConvertFieldsExceptionTest extends VerifoneTest
{
    public function testConstructMessageWithoutParameter()
    {
        $expectedMessage = 'Was unable to convert fields to desired format';
        $exception = new UnableToConvertFieldsException();

        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    /**
     * @param $message
     *
     * @dataProvider providerTestConstructMessageWithEmptyParameters
     */
    public function testConstructMessageWithEmptyParameters($message)
    {
        $expectedMessage = 'Was unable to convert fields to desired format';
        $exception = new UnableToConvertFieldsException($message);

        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function providerTestConstructMessageWithEmptyParameters()
    {
        return array(
            array(null),
            array(''),
            array(false),
        );
    }

    /**
     * @param $message
     *
     * @dataProvider providerTestConstructMessageWithActualMessages
     */
    public function testConstructMessageWithActualMessages($message)
    {
        $expectedMessage = $message;
        $exception = new UnableToConvertFieldsException($message);

        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function providerTestConstructMessageWithActualMessages()
    {
        return array(
            array('newValue'),
            array('asdfas asfd weiiiwer sf'),
            array(123),
        );
    }
}
