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
        $result = $transportImpl->changeDefaultConfiguration($config);
        $this->assertFalse($result);
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

    /**
     * @param $config
     *
     * @dataProvider providerTestChangeDefaultConfigurationHappy
     */
    public function testChangeDefaultConfigurationHappy($config)
    {
        $transportImpl = new TransportImpl($this->transport);
        $result = $transportImpl->changeDefaultConfiguration($config);
        $this->assertTrue($result);
    }
    
    public function providerTestChangeDefaultConfigurationHappy()
    {
        return array(
            array(array('jee' => 'jee', 'jeer2' => 'jee')),
            array(array('asdf')),
        );
    }

    public function testRequestTrue()
    {
        $transportImpl = new TransportImpl($this->transport);
        $response =$this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $this->transport->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));

        $result = $transportImpl->request('http://localhost', 'data');
        $this->assertNotFalse($result);
    }

    /**
     * @param $code
     * @throws TransportationFailedException
     *
     * @dataProvider providerTestRequestWrongResponseCode
     */
    public function testRequestWrongResponseCode($code)
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
        $transportImpl->request('http://localhost', 'data');
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

    public function testRequestFalse()
    {
        $transportImpl = new TransportImpl($this->transport);
        $this->transport->expects($this->once())
            ->method('post')
            ->will($this->returnValue(false));

        $this->expectException(TransportationFailedException::class);
        $transportImpl->request('http://localhost', 'data');
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
