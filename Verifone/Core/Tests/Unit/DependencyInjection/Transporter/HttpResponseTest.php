<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependencyInjection\Transporter;

use Lamia\Validation\Exception\FieldValidationFailedException;
use Verifone\Core\DependencyInjection\Transporter\HttpResponse;

class HttpResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $statusCode
     *
     * @dataProvider providerTestGetStatusCode
     */
    public function testGetStatusCode($statusCode)
    {
        $response = new HttpResponse($statusCode, "afdasdf\r\n\r\n");
        $result = $response->getStatusCode();
        $this->assertEquals($statusCode, $result);
    }

    public function providerTestGetStatusCode()
    {
        return array(
            array(100),
        );
    }

    /**
     * @param $data
     *
     * @dataProvider providerTestGetFull
     */
    public function testGetFull($data)
    {
        $response = new HttpResponse(200, $data);
        $result = $response->getFull();
        $this->assertEquals($data, $result);
    }

    public function providerTestGetFull()
    {
        return array(
            array("asdfasd\r\n\r\n"),
            array("\r\n\r\n"),
            array("adfasdf\r\n\r\nsdgfsgdffg"),
            array("\r\n\r\nsdgfsgdffg"),
        );
    }

    /**
     * @param $data
     * @param $expected
     *
     * @dataProvider providerTestGetHeader
     */
    public function testGetHeader($data, $expected)
    {
        $response = new HttpResponse(200, $data);
        $result = $response->getHeader();
        $this->assertEquals($expected, $result);
    }

    public function providerTestGetHeader()
    {
        return array(
            array("asdff\r\n\r\ngggg", 'asdff'),
            array("ggg\r\n\r\n", 'ggg'),
            array("\r\n\r\ngggg", ''),
        );
    }

    /**
     * @param $data
     * @param $expected
     *
     * @dataProvider providerTestGeBody
     */
    public function testGetBody($data, $expected)
    {
        $response = new HttpResponse(200, $data);
        $result = $response->getBody();
        $this->assertEquals($expected, $result);
    }

    public function providerTestGeBody()
    {
        return array(
            array("asdff\r\n\r\ngggg", 'gggg'),
            array("ggg\r\n\r\n", ''),
            array("\r\n\r\ngggg", 'gggg'),
        );
    }

    /**
     * @param $data
     *
     * @dataProvider providerTestFailCases
     */
    public function testFailCases($data)
    {
        $this->expectException(FieldValidationFailedException::class);
        $response = new HttpResponse(200, $data);
    }

    public function providerTestFailCases()
    {
        return array(
            array(''),
            array(true),
            array(false),
            array(123),
            array(array()),
            array('asdfasfd'),
            array("asdff\r\n\r"),
        );
    }

}
