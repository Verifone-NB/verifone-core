<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Executor;

use Lamia\Validation\Exception\FieldValidationFailedException;
use Verifone\Core\Exception\ResponseCheckFailedException;
use Verifone\Core\Executor\BackendServiceExecutor;
use Verifone\Core\Exception\TransportationFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

class BackendServiceExecutorTest extends VerifoneTest
{
    private $service;
    private $storage;
    private $transport;
    private $executor;
    private $validation;
    private $crypto;
    private $converter;
    private $cutter;

    public function setUp()
    {
        $this->service = $this->getMockBuilder('\Verifone\Core\Service\Backend\BackendService')->getMock();
        $this->transport = $this->getMockBuilder('\Verifone\Core\Transport\Transport')->getMock();
        $this->storage = $this->getMockBuilder('\Verifone\Core\Storage\Storage')->getMock();
        $this->validation = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Validation\CommonValidation')->getMock();
        $this->crypto = $this->getMockBuilder('\Verifone\Core\DependencyInjection\CryptUtils\CryptUtil')->getMock();
        $this->converter = $this->getMockBuilder('\Verifone\Core\Converter\Response\ResponseConverter')->getMock();
        $this->cutter = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Utils\Cutter')->getMock();

        $this->service->expects($this->once())
            ->method('getFields')
            ->willReturn($this->storage);
        
        $this->executor = new BackendServiceExecutor(
            $this->validation,
            $this->crypto,
            $this->transport,
            $this->converter,
            $this->cutter
        );
    }

    public function testErroneousUrls()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));
        $this->cutter->expects($this->once())
            ->method('cutFields')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));

        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn('');

        $this->expectException(FieldValidationFailedException::class);
        $this->executor->executeService($this->service, 'test');
    }

    public function testErroneousUrls2()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));
        $this->cutter->expects($this->once())
            ->method('cutFields')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));

        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn(array(true));
        $this->transport->expects($this->once())
            ->method('post')
            ->will($this->throwException(new TransportationFailedException('', '')));

        $this->expectException(TransportationFailedException::class);
        $this->executor->executeService($this->service, 'test');
    }

    public function testErroneousUrls3()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));
        $this->cutter->expects($this->once())
            ->method('cutFields')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));

        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn(array());

        $this->transport->expects($this->never())->method('post');

        $this->expectException(ResponseCheckFailedException::class);
        $this->executor->executeService($this->service, 'test');
    }
    
}
