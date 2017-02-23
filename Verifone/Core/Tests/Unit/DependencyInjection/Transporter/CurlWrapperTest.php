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

use Verifone\Core\DependencyInjection\Transporter\CurlWrapper;

class CurlWrapperTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructAndCloseAndOptions()
    {
        $curl = new CurlWrapper();
        $curl->setOption(CURLOPT_USERAGENT, 'agent Bond');
        $curl->setOption(CURLOPT_HEADER, true);
        $curl->addHeader('Content-type', 'application/x-www-form-urlencoded');
        $curl->addHeader('Connection', 'close');
        $curl->close();
        $this->assertTrue(true);
    }

    public function testPost()
    {
        $curl = new CurlWrapper();
        $response = $curl->post('http://localhost/', array('jee' => 'value', 'jee2' => 'value2'));
        $curl->close();
        $this->assertNotFalse($response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPost404()
    {
        $curl = new CurlWrapper();
        $response = $curl->post('http://localhost/index/index/index.php', array('jee' => 'value', 'jee2' => 'value2'));
        $curl->close();
        $this->assertFalse($response);
    }

    public function testPostWrongUrl()
    {
        $curl = new CurlWrapper();
        $response = $curl->post('asdf', 'asdfasdfah asdflj ggg');
        $curl->close();
        $this->assertFalse($response);
    }
}
