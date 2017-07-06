<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependencyInjection\Validation\Response;


use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\DependencyInjection\Validation\Response\BackendResponseValidation;
use Verifone\Core\Tests\Unit\VerifoneTest;

class BackendResponseValidationTest extends VerifoneTest
{
    private $utils;

    public function setUp()
    {
        $this->utils = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Validation\Response\ResponseValidationUtils')->getMock();
    }

    public function testConstruct()
    {
        new BackendResponseValidation($this->utils);
    }

    public function testValidationSuccess()
    {
        $validation = new BackendResponseValidation($this->utils);
        $this->utils->expects($this->once())->method('matches');
        $this->utils->expects($this->once())->method('matchesAll');
        $this->utils->expects($this->once())->method('fieldsExist');
        $this->utils->expects($this->once())->method('checkErrorMessage');
        $this->utils->expects($this->once())->method('verifySignature');
        $validation->validate(
            array(FieldConfigImpl::OPERATION => 'asdf', FieldConfigImpl::REQUEST_ID => '123'),
            array(FieldConfigImpl::OPERATION => 'asdf2', FieldConfigImpl::REQUEST_ID => 123, FieldConfigImpl::RESPONSE_ID => 123),
            'asdfasdfasdf');
    }
}
