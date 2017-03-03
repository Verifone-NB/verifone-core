<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Validation;

use Verifone\Core\DependencyInjection\Validation\VerifoneValidation;

class VerifoneValidationTest extends \PHPUnit_Framework_TestCase
{
    private $fieldValidation;
    private $responseValidation;

    public function setUp()
    {
        $this->fieldValidation = $this->getMockBuilder('\Lamia\Validation\Validation\Interfaces\Validation')->getMock();
        $this->responseValidation = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Validation\Response\ResponseValidation')->getMock();
    }

    public function testConstruct()
    {
        new VerifoneValidation($this->fieldValidation, $this->responseValidation);
    }

    public function testValidateRequestFields()
    {
        $validation = new VerifoneValidation($this->fieldValidation, $this->responseValidation);
        $this->fieldValidation->expects($this->once())
            ->method('validateFields')
            ->with($this->arrayHasKey('test'));
        $this->responseValidation->expects($this->never())
            ->method('validate');
        $validation->validate(array('test' => 'aaa'));
    }

    public function testValidateResponse()
    {
        $validation = new VerifoneValidation($this->fieldValidation, $this->responseValidation);
        $this->fieldValidation->expects($this->never())
            ->method('validateFields');
        $this->responseValidation->expects($this->once())
            ->method('validate')
            ->with(
                $this->arrayHasKey('test1'),
                $this->arrayHasKey('test2'),
                $this->stringContains('gbbasdfga')
            );
        $validation->validateResponse(array('test1' => 'aaa'), array('test2' => 'aaa'), 'gbbasdfga');
    }
}
