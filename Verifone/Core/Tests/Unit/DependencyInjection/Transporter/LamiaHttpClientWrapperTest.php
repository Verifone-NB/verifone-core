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

use Verifone\Core\DependencyInjection\Transporter\LamiaHttpClientWrapper;
use Verifone\Core\Tests\Unit\VerifoneTest;

class LamiaHttpClientWrapperTest extends VerifoneTest
{
    public function testConstructAndCloseAndOptions()
    {
        $httpClientWrapper = new LamiaHttpClientWrapper();
        $httpClientWrapper->setOption(CURLOPT_USERAGENT, 'agent Bond');
        $httpClientWrapper->setOption(CURLOPT_HEADER, true);
        $httpClientWrapper->addHeader('Content-type', 'application/x-www-form-urlencoded');
        $httpClientWrapper->addHeader('Connection', 'close');
        $httpClientWrapper->close();
        $this->assertTrue(true);
    }

    public function testPost404()
    {
        $httpClientWrapper = new LamiaHttpClientWrapper();
        $response = $httpClientWrapper->post('http://localhost/index/index/index.php', array('jee' => 'value', 'jee2' => 'value2'));
        $httpClientWrapper->close();
        $this->assertFalse($response);
    }

    public function testPostWrongUrl()
    {
        $httpClientWrapper = new LamiaHttpClientWrapper();
        $response = $httpClientWrapper->post('asdf', 'asdfasdfah asdflj ggg');
        $httpClientWrapper->close();
        $this->assertFalse($response);
    }
}
