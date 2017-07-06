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

use Verifone\Core\Exception\StorageValueOverwriteException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class StorageValueOverWriteExceptionTest
 * @package Verifone\Core\Tests\Exception
 * @codeCoverageIgnore
 */
class StorageValueOverWriteExceptionTest extends VerifoneTest
{
    /**
     * @param $key
     * @param $newValue
     * @param $oldValue
     *
     * @dataProvider providerTestConstructMessage
     */
    public function testConstructMessage($key, $newValue, $oldValue)
    {
        $expectedMessage = "Trying to store a value " . $newValue . " with key " . $key .
            " but key already exists with value " . $oldValue;
        $exception = new StorageValueOverwriteException($key, $newValue, $oldValue);

        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function providerTestConstructMessage()
    {
        return array(
            array('key1', 'newValue', 'oldValue'),
            array('key1', null, 'oldValue'),
            array(null, 'newnewnew afds', 'asdfas asfd weiiiwer sf'),
            array('131a', 'asdf', null),
            array(null, null, null),
            array('', '', ''),
            array(false, true, false),
            array(123, 11, 11),
        );
    }
}
