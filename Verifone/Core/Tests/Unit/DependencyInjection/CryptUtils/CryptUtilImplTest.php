<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependencyInjection\CryptUtils;


use Verifone\Core\DependencyInjection\CryptUtils\CryptUtilImpl;
use \TypeError;
use Verifone\Core\Exception\CryptUtilException;

class CryptUtilImplTest extends \PHPUnit_Framework_TestCase
{
    private $cryptUtils;
    private $mockCryptography;

    public function setUp()
    {
        $this->mockCryptography = $this->getMockBuilder('\Verifone\Core\DependencyInjection\CryptUtils\Cryptography')
            ->getMock();
        $this->cryptUtils = new CryptUtilImpl($this->mockCryptography);
    }

    public function testConstructor()
    {
        $this->expectException(TypeError::class);
        new CryptUtilImpl(null);
    }

    /**
     * @param $privateKey
     * @param $fields
     * @dataProvider providerTestGenerateSignatureOneHappy
     */
    public function testGenerateSignatureOneHappy($privateKey, $fields)
    {
        $this->mockCryptography->expects($this->once())
            ->method('sign')
            ->will($this->returnValue('aa'));
        $result = $this->cryptUtils->generateSignatureOne($privateKey, $fields);
        $this->assertEquals('6161', $result);
    }

    public function providerTestGenerateSignatureOneHappy()
    {
        return array(
            array('adfafdsf', array('adfas', '123afsfd', 'fff')),
            array('124ujmöfg9+82nrsd.mvnajkfhadfd98a', array('aa' => 'bb', 'cc' => 'dd', 'ee' => 'ff')),
        );
    }

    /**
     * @param $privateKey
     * @param $fields
     * @dataProvider providerTestGenerateSignatureOneSad
     */
    public function testGenerateSignatureOneSad($privateKey, $fields)
    {
        $this->expectException(CryptUtilException::class);
        $this->cryptUtils->generateSignatureOne($privateKey, $fields);
    }

    public function providerTestGenerateSignatureOneSad()
    {
        return array(
            // private key
            array('', array('a' => 'aa')),
            array(null, array('a' => 'aa')),
            array(true, array('a' => 'aa')),
            array(false, array('a' => 'aa')),
            array(124, array('a' => 'aa')),

            // $fields
            array('a', ''),
            array('a', null),
            array('a', true),
            array('a', false),
            array('a', 123),
            array('a', 'aaaa'),
        );
    }

    public function testVerifySignatureValid()
    {
        $this->mockCryptography->expects($this->once())
            ->method('verify')
            ->will($this->returnValue(true));
        $result = $this->cryptUtils->verifyResponseFieldsSignature('aa', array('s-t-256-256_signature-one' => 'a'));
        $this->assertTrue($result);
    }

    public function testVerifySignatureInvalid()
    {
        $this->mockCryptography->expects($this->once())
            ->method('verify')
            ->will($this->returnValue(false));
        $result = $this->cryptUtils->verifyResponseFieldsSignature('aa', array('s-t-256-256_signature-one' => 'a'));
        $this->assertFalse($result);
    }

    /**
     * @param $key
     * @param $data
     * @param $signature
     *
     * @dataProvider providerTestVerifySignatureParameterValidationFailed
     */
    public function testVerifySignatureParameterValidationFailed($key, $data)
    {
        $this->expectException(CryptUtilException::class);
        $this->cryptUtils->verifyResponseFieldsSignature($key, $data);
    }

    public function providerTestVerifySignatureParameterValidationFailed()
    {
        return array(
            // $key
            array('', array('s-t-256-256_signature-one' => 'aaa')),
            array(null, array('s-t-256-256_signature-one' => 'aaa')),
            array(false, array('s-t-256-256_signature-one' => 'aaa')),
            array(true, array('s-t-256-256_signature-one' => 'aaa')),
            array(123, array('s-t-256-256_signature-one' => 'aaa')),
            array(array(), array('s-t-256-256_signature-one' => 'aaa')),

            // data
            array('a', ''),
            array('a', null),
            array('a', true),
            array('a', false),
            array('a', 123),
            array('a', 'aaa'),

            // signature
            array('a', array('s-t-256-256_signature-one' => '')),
            array('a', array('s-t-256-256_signature-one' => null)),
            array('a', array('s-t-256-256_signature-one' => true)),
            array('a', array('s-t-256-256_signature-one' => false)),
            array('a', array('s-t-256-256_signature-one' => 123),
            array('a', array('s-t-256-256_signature-one' => array()))),
        );
    }
}
