<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Transport;

use Verifone\Core\Transport\TransportImpl;
use Verifone\Core\Exception\TransportationFailedException;

/**
 * Class CurlTransportTest
 * @package Verifone\Core\Tests\Transport
 * @codeCoverageIgnore
 */
class TransportImplTest extends \PHPUnit_Framework_TestCase
{
    protected $transport;

    public function setUp()
    {
        $this->transport = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationWrapper')->getMock();
    }

    public function testConstructor()
    {
        $this->transport->expects($this->once())->method('setTimeout')->with($this->equalTo(30));
        $this->transport->expects($this->once())->method('setMaxRedirects')->with($this->equalTo(0));
        $this->transport->expects($this->exactly(2))->method('addHeader')
            ->withConsecutive(
                array($this->equalTo('Content-type'), $this->equalTo('application/x-www-form-urlencoded')),
                array($this->equalTo('Connection'), $this->equalTo('close'))
            );
        $transportImpl = new TransportImpl($this->transport);
    }

    /**
     * @param $config
     *
     * @dataProvider providerTestChangeDefaultConfigurationSad
     */
    public function testChangeDefaultConfigurationSad($config)
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->expectException(\TypeError::class);
        $result = $transportImpl->changeDefaultConfiguration($config);
    }

    public function providerTestChangeDefaultConfigurationSad()
    {
        return array(
            array(''),
            array(false),
            array(true),
            array('asdfas asdf asds'),
            array(1234),
            array('0'),
        );
    }

    public function testChangeDefaultConfigurationHappy1()
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->transport->expects($this->exactly(2))->method('setOption')
            ->withConsecutive(
                array($this->equalTo('jee123'), $this->equalTo('jee321')),
                array($this->equalTo('jeer2'), $this->equalTo('jee'))
            );
        $result = $transportImpl->changeDefaultConfiguration(array('jee123' => 'jee321', 'jeer2' => 'jee'));
        $this->assertTrue($result);
    }

    public function testChangeDefaultConfigurationHappy2()
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->transport->expects($this->once())
            ->method('setOption')
            ->with($this->equalTo('0'), $this->equalTo('asdf'));
        $result = $transportImpl->changeDefaultConfiguration(array('asdf'));
        $this->assertTrue($result);
    }

    public function testPostTrue()
    {
        $transportImpl = new TransportImpl($this->transport);
        $response =$this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->transport->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $result = $transportImpl->post('http://localhost', 'data');
        $this->assertNotFalse($result);
    }

    public function testGetTrue()
    {
        $transportImpl = new TransportImpl($this->transport);
        $response = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->transport->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $result = $transportImpl->get('http://localhost');
        $this->assertNotFalse($result);
    }


    /**
     * @param $code
     * @throws TransportationFailedException
     *
     * @dataProvider providerTestRequestWrongResponseCode
     */
    public function testRequestPostResponseCode($code)
    {
        $transportImpl = new TransportImpl($this->transport);
        $response =$this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($code));
        $this->transport->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $this->expectException(TransportationFailedException::class);
        $transportImpl->post('http://localhost', 'data');
    }

    /**
     * @param $code
     * @throws TransportationFailedException
     *
     * @dataProvider providerTestRequestWrongResponseCode
     */
    public function testGetWrongResponseCode($code)
    {
        $transportImpl = new TransportImpl($this->transport);
        $response =$this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($code));
        $this->transport->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $this->expectException(TransportationFailedException::class);
        $transportImpl->get('http://localhost');
    }

    public function providerTestRequestWrongResponseCode()
    {
        return array(
            array(205),
            array('200'),
            array(''),
            array(null),
            array(false),
            array(true)
        );
    }

    public function testPostFalse()
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->transport->expects($this->once())
            ->method('post')
            ->will($this->returnValue(false));

        $this->expectException(TransportationFailedException::class);
        $transportImpl->post('http://localhost', 'data');
    }

    public function testGetFalse()
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->transport->expects($this->once())
            ->method('get')
            ->will($this->returnValue(false));

        $this->expectException(TransportationFailedException::class);
        $transportImpl->get('http://localhost');
    }

    public function testTransportationWrapperNull()
    {
        $this->expectException(\TypeError::class);
        new TransportImpl(null);
    }

    public function testClose()
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->transport->expects($this->once())
            ->method('close');
        $transportImpl->close();
    }
}
