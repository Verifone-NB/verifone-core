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


use Verifone\Core\Exception\StorageKeyNotInKeyspaceException;

/**
 * Class StorageKeyNotInKeyspaceExceptionTest
 * @package Verifone\Core\Tests\Exception
 * @codeCoverageIgnore
 */
class StorageKeyNotInKeyspaceExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $key
     * @param $value
     *
     * @dataProvider providerTestConstructMessage
     */
    public function testConstructMessage($key, $value)
    {
        $expectedMessage = "Trying to store a value " . $value . " with key " . $key .
            " but key is not in available keyspace";

        $exception = new StorageKeyNotInKeyspaceException($key, $value);

        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function providerTestConstructMessage()
    {
        return array(
            array('key1', 'newValue'),
            array('key1', null),
            array(null, 'asdfas asfd weiiiwer sf'),
            array(null, null),
            array('', ''),
            array(false, true),
            array(123, 11),
        );
    }
}
