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


use Verifone\Core\Executor\FrontendServiceExecutor;
use Verifone\Core\Tests\Unit\VerifoneTest;

class FrontendServiceExecutorTest extends VerifoneTest
{
    private $validation;
    private $converter;
    private $storage;
    private $service;
    private $executor;
    private $transport;

    public function setUp()
    {
        $this->validation = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Validation\CommonValidation')->getMock();
        $this->converter = $this->getMockBuilder('\Verifone\Core\Converter\Request\RequestConverter')->getMock();
        $this->storage = $this->getMockBuilder('\Verifone\Core\Storage\Storage')->getMock();
        $this->service = $this->getMockBuilder('\Verifone\Core\Service\Frontend\FrontendService')->getMock();
        $this->transport = $this->getMockBuilder('\Verifone\Core\Transport\Transport')->getMock();

        $this->service->expects($this->once())
            ->method('getFields')
            ->willReturn($this->storage);
        $this->executor = new FrontendServiceExecutor($this->validation, $this->converter, $this->transport);
    }

    public function testExecute()
    {
        $this->storage->expects($this->once())
            ->method('getAsArray')
            ->willReturn(array());
        $this->validation->expects($this->once())
            ->method('validate')
            ->with(array());
        $this->converter->expects($this->once())
            ->method('convert')
            ->with($this->storage, 'asfd');
        $this->executor->executeService($this->service, array('asfd'));
    }
}
