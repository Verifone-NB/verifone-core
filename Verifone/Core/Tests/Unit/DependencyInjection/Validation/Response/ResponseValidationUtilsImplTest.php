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
use Verifone\Core\DependencyInjection\Validation\Response\ResponseValidationUtilsImpl;
use Verifone\Core\Exception\ResponseCheckFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

class ResponseValidationUtilsImplTest extends VerifoneTest
{
    private $cryptUtil;

    public function setUp()
    {
        $this->cryptUtil = $this->getMockBuilder('\Verifone\Core\DependencyInjection\CryptUtils\CryptUtil')->getMock();
    }

    public function testConstruct()
    {
        new ResponseValidationUtilsImpl($this->cryptUtil);
    }

    public function testVerifySignatureSuccess()
    {
        $this->cryptUtil->expects($this->once())
            ->method('verifyResponseFieldsSignature')
            ->willReturn(true);
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $validationUtils->verifySignature(array(), 'asdf');
    }

    public function testVerifySignatureFail()
    {
        $this->cryptUtil->expects($this->once())
            ->method('verifyResponseFieldsSignature')
            ->willReturn(false);
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $this->expectException(ResponseCheckFailedException::class);
        $validationUtils->verifySignature(array(), 'asdf');
    }

    /**
     * @param $value1
     * @param $value2
     * @throws ResponseCheckFailedException
     * @dataProvider providerTestMatches
     */
    public function testMatches($value1, $value2)
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $validationUtils->matches($value1, $value2, 'aaaa', 'aaa');
    }

    public function providerTestMatches()
    {
        return array(
            array('a', 'a'),
            array(true, true),
            array(false, false),
            array(null, null),
            array('', ''),
            array(0, 0),
            array(1, 1)
        );
    }

    /**
     * @param $value1
     * @param $value2
     * @throws ResponseCheckFailedException
     * @dataProvider providerTestMatchesFail
     */
    public function testMatchesFail($value1, $value2)
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $this->expectException(ResponseCheckFailedException::class);
        $validationUtils->matches($value1, $value2, 'aaaa', 'aaa');
    }

    public function providerTestMatchesFail()
    {
        return array(
            array('a', 'b'),
            array(true, false),
            array('', false),
            array(0, '')
        );
    }

    public function testCheckErrorMessageExists()
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $this->expectException(ResponseCheckFailedException::class);
        $validationUtils->checkErrorMessage(array(FieldConfigImpl::RESPONSE_ERROR_MESSAGE => 'asdfasdfasdf'), 'asdf');
    }

    public function testCheckErrorMessageDoesntExist()
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $validationUtils->checkErrorMessage(array(), 'asdf');
    }

    public function testCheckErrorMessageEmpty()
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $validationUtils->checkErrorMessage(array(FieldConfigImpl::RESPONSE_ERROR_MESSAGE => ''), 'asdf');
    }

    /**
     * @dataProvider providerTestFieldsExistFail
     */
    public function testFieldsExistFail($fields, $mandatory)
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $this->expectException(ResponseCheckFailedException::class);
        $validationUtils->fieldsExist($fields, $mandatory);
    }

    public function providerTestFieldsExistFail()
    {
        return array(
            array(array('1' => 'asdf', '2' => 'asdf', '3' => 'ff'), array('1', '4')),
            array(array(), array('1')),
        );
    }

    /**
     * @param $fields
     * @param $mandatory
     * @throws ResponseCheckFailedException
     *
     * @dataProvider providerTestFieldsExistSuccess
     */
    public function testFieldsExistSuccess($fields, $mandatory)
    {
        $validationUtils = new ResponseValidationUtilsImpl($this->cryptUtil);
        $validationUtils->fieldsExist($fields, $mandatory);
    }

    public function providerTestFieldsExistSuccess()
    {
        return array(
            array(array('1' => 'asdf', '2' => 'asdf', '3' => 'ff'), array('1')),
            array(array('1' => 'asdf', '2' => 'asdf', '3' => 'ff'), array('1', '2', '3')),
            array(array('1' => 'asdf', '2' => 'asdf', '3' => 'ff'), array()),
            array(array(), array()),
        );
    }
}
