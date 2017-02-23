<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Converter\Response;


use Verifone\Core\Converter\Response\ArrayResponseConverter;

class ArrayResponseConverterTest extends \PHPUnit_Framework_TestCase
{
    private $mockResponse;
    private $converter;

    public function setUp()
    {
        $this->mockResponse = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')->getMock();
        $this->converter = new ArrayResponseConverter();
    }

    /**
     * @param $body
     *
     * @dataProvider providerTestConvert
     */
    public function testConvert($body, $expected = array())
    {
        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($body));
        $result = $this->converter->convert($this->mockResponse);
        $this->assertEquals($result, $expected);
    }

    public function providerTestConvert()
    {
        return array(
            array('asdf'),
            array(array()),
            array(null),
            array(true),
            array(false),
            array(21),
            array(''),
            array('aa=bb&cc=dd&ee=ff', array('aa' => 'bb', 'cc' => 'dd', 'ee' => 'ff')),
            array('aa=bb&cc=dd&ee=2016-01-11+09%3A01%3A02', array('aa' => 'bb', 'cc' => 'dd', 'ee' => '2016-01-11 09:01:02')),
            array('aa=bb&cc=dd&ee=2016-01-11+222', array('aa' => 'bb', 'cc' => 'dd', 'ee' => '2016-01-11+222')),
            array('=bb&cc=', array('' => 'bb', 'cc' => '')),
            array('aa=bb&aa=cc', array('aa' => 'cc')),
            array('&cc=dd&ee=ff&', array('cc' => 'dd', 'ee' => 'ff')),
            array('bb&cc', array()),
        );
    }
}
