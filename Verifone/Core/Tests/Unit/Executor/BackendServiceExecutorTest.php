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

    public function setUp(): void
    {
        $this->service = $this->getMockBuilder('\Verifone\Core\Service\Backend\BackendService')->getMock();
        $this->transport = $this->getMockBuilder('\Verifone\Core\Transport\Transport')->getMock();
        $this->storage = $this->getMockBuilder('\Verifone\Core\Storage\Storage')->getMock();
        $this->validation = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Validation\CommonValidation')->getMock();
        $this->crypto = $this->getMockBuilder('\Verifone\Core\DependencyInjection\CryptUtils\CryptUtil')->getMock();
        $this->converter = $this->getMockBuilder('\Verifone\Core\Converter\Response\ResponseConverter')->getMock();

        $this->service->expects($this->once())
            ->method('getFields')
            ->willReturn($this->storage);
        
        $this->executor = new BackendServiceExecutor(
            $this->validation,
            $this->crypto,
            $this->transport,
            $this->converter
        );
    }

    public function testErroneousUrls()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
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
        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn(array());

        $this->transport->expects($this->never())->method('post');

        $this->expectException(ResponseCheckFailedException::class);
        $this->executor->executeService($this->service, 'test');
    }

    public function testFailoverToNextUrl()
    {
        $requestFields = array(
            'i-f-1-3_currency-code' => '978',
        );
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn($requestFields);
        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn(array('url1', 'url2'));
        $this->service->expects($this->once())
            ->method('getMatchingFields')
            ->willReturn(array());

        $serviceResponseConverter = $this->getMockBuilder('\Verifone\Core\Converter\Response\ResponseConverter')->getMock();
        $serviceResponseConverter->expects($this->once())
            ->method('convert')
            ->willReturn('converted');
        $this->service->expects($this->once())
            ->method('getResponseConverter')
            ->willReturn($serviceResponseConverter);

        $response = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $this->transport->expects($this->exactly(2))
            ->method('post')
            ->withConsecutive(array('url1', $requestFields), array('url2', $requestFields))
            ->will($this->onConsecutiveCalls(
                $this->throwException(new TransportationFailedException('url1', '')),
                $response
            ));
        $this->converter->expects($this->once())
            ->method('convert')
            ->with($response)
            ->willReturn(array());

        $this->assertEquals('converted', $this->executor->executeService($this->service, 'test'));
    }

    public function testAllUrlsFailThrowsLastException()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));
        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn(array('url1', 'url2'));

        $lastException = new TransportationFailedException('url2', '');
        $this->transport->expects($this->exactly(2))
            ->method('post')
            ->will($this->onConsecutiveCalls(
                $this->throwException(new TransportationFailedException('url1', '')),
                $this->throwException($lastException)
            ));

        try {
            $this->executor->executeService($this->service, 'test');
            $this->fail('Expected TransportationFailedException');
        } catch (TransportationFailedException $e) {
            $this->assertSame($lastException, $e);
        }
    }

    public function testNoFailoverOnResponseValidationFailure()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn(array(
                'i-f-1-3_currency-code' => '978',
            ));
        $this->service->expects($this->once())
            ->method('getUrls')
            ->willReturn(array('url1', 'url2'));
        $this->service->expects($this->once())
            ->method('getMatchingFields')
            ->willReturn(array());

        $response = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Transporter\TransportationResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $this->transport->expects($this->once())
            ->method('post')
            ->willReturn($response);
        $this->converter->expects($this->once())
            ->method('convert')
            ->willReturn(array());
        $this->validation->expects($this->once())
            ->method('validateResponse')
            ->will($this->throwException(new ResponseCheckFailedException('invalid')));

        $this->expectException(ResponseCheckFailedException::class);
        $this->executor->executeService($this->service, 'test');
    }
    
}
