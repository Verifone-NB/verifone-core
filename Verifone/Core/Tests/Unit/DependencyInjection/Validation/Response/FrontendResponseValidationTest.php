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
use Verifone\Core\DependencyInjection\Validation\Response\FrontendResponseValidation;
use Verifone\Core\Tests\Unit\VerifoneTest;

class FrontendResponseValidationTest extends VerifoneTest
{
    private $utils;

    public function setUp()
    {
        $this->utils = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Validation\Response\ResponseValidationUtils')->getMock();
    }

    public function testConstruct()
    {
        new FrontendResponseValidation($this->utils);
    }

    public function testValidationCancel()
    {
        $validation = new FrontendResponseValidation($this->utils);
        $this->utils->expects($this->once())->method('matches');
        $this->utils->expects($this->exactly(2))->method('fieldsExist');
        $this->utils->expects($this->once())->method('verifySignature');
        $validation->validate(
            array(FieldConfigImpl::ORDER_NUMBER => 123),
            array(FieldConfigImpl::RESPONSE_CANCEL_REASON => 'asdf2', FieldConfigImpl::ORDER_NUMBER => 123),
            'asdfasdfasdf');
    }

    public function testValidationSuccess()
    {
        $validation = new FrontendResponseValidation($this->utils);
        $this->utils->expects($this->exactly(4))->method('matches');
        $this->utils->expects($this->exactly(2))->method('fieldsExist');
        $this->utils->expects($this->once())->method('verifySignature');
        $validation->validate(
            array(
                FieldConfigImpl::ORDER_NUMBER => 123,
                FieldConfigImpl::ORDER_TIMESTAMP => '13',
                FieldConfigImpl::ORDER_TOTAL_INCL_TAX => '123',
                FieldConfigImpl::ORDER_CURRENCY => '123'
            ),
            array(
                FieldConfigImpl::ORDER_NUMBER => 123,
                FieldConfigImpl::ORDER_TIMESTAMP => '13',
                FieldConfigImpl::ORDER_TOTAL_INCL_TAX => '123',
                FieldConfigImpl::ORDER_CURRENCY => '123'
            ),
            'asdfasdfasdf');
    }
}

