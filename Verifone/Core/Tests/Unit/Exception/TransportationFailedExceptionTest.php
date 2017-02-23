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

use Verifone\Core\Exception\TransportationFailedException;

/**
 * Class CurlFailedExceptionTest
 * @package Verifone\Core\Tests\Exception
 * @codeCoverageIgnore
 */
class TransportationFailedExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $message
     * @dataProvider providerTestConstructMessage
     */
    public function testConstructMessage($url, $data)
    {
        $expectedMessage = 'Trying to transport to ' . $url . ' with data ' . $data . ' failed.';

        $exception = new TransportationFailedException($url, $data);

        $this->assertEquals($expectedMessage, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertEquals(null, $exception->getPrevious());
    }

    public function providerTestConstructMessage()
    {
        return array(
            array('newValue', 'value2'),
            array(null, null),
            array('asdfas asfd weiiiwer sf', 'aaa dsaga bdsfiu'),
            array('', ''),
            array(false, true),
            array(123, 234256),
        );
    }
}
