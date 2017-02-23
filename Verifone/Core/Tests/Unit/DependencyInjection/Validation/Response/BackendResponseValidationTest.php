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


use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\Validation\Response\BackendResponseValidation;

class BackendResponseValidationTest extends \PHPUnit_Framework_TestCase
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
        $this->utils->expects($this->exactly(3))->method('matches');
        $this->utils->expects($this->exactly(2))->method('fieldsExist');
        $this->utils->expects($this->once())->method('checkErrorMessage');
        $this->utils->expects($this->once())->method('verifySignature');
        $validation->validate(
            array(FieldConfig::OPERATION => 'asdf', FieldConfig::REQUEST_ID => '123'),
            array(FieldConfig::OPERATION => 'asdf2', FieldConfig::REQUEST_ID => 123, FieldConfig::RESPONSE_ID => 123),
            'asdfasdfasdf');
    }
}
