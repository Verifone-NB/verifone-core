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

use Verifone\Core\Exception\ResponseCheckFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class FieldValidationFailedExceptionTest
 * @package Verifone\Core\Tests\Exception
 * @codeCoverageIgnore
 */
class ResponseCheckFailedExceptionTest extends VerifoneTest
{
    /**
     * @param $message
     * @dataProvider providerTestConstructMessage
     */
    public function testConstructMessage($message)
    {
        $expectedMessage = $message;

        $exception = new ResponseCheckFailedException($message);

        $this->assertEquals($expectedMessage, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertEquals(null, $exception->getPrevious());
    }

    public function providerTestConstructMessage()
    {
        return array(
            array('newValue'),
            array(null),
            array('asdfas asfd weiiiwer sf'),
            array(''),
            array(false),
            array(123),
        );
    }
}
