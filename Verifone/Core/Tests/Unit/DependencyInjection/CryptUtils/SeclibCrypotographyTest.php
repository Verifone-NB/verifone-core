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


use Verifone\Core\DependencyInjection\CryptUtils\SeclibCryptography;
use Verifone\Core\Exception\CryptUtilException;
use Verifone\Core\Tests\Unit\VerifoneTest;

class SeclibCrypotographyTest extends VerifoneTest
{
    /**
     * @param $privateKey
     * @param $fields
     * @dataProvider providerTestGenerateSignatureOneSad
     */
    public function testGenerateSignatureOneSad($privateKey, $data)
    {
        $crypto = new SeclibCryptography();
        $this->expectException(CryptUtilException::class);
        $crypto->sign($data, $privateKey);
    }

    public function providerTestGenerateSignatureOneSad()
    {
        return array(
            // private key
            array('', 'a'),
            array(null, 'a'),
            array(true, 'a'),
            array(false, 'a'),
            array(124, 'a'),

            // data
            array('a', ''),
            array('a', null),
            array('a', true),
            array('a', false),
            array('a', 123),
        );
    }

    /**
     * @param $key
     * @param $data
     * @param $signature
     *
     * @dataProvider providerTestVerifySad
     */
    public function testVerifySad($key, $data, $signature)
    {
        $crypto = new SeclibCryptography();
        $this->expectException(CryptUtilException::class);
        $crypto->verify($key, $data, $signature);
    }

    public function providerTestVerifySad()
    {
        return array(
            // $key
            array('', 'a', 'a'),
            array(null, 'a', 'a'),
            array(false, 'a', 'a'),
            array(true, 'a', 'a'),
            array(123, 'a', 'a'),
            array(array(), 'a', 'a'),

            // data
            array('a', '', 'a'),
            array('a', null, 'a'),
            array('a', true, 'a'),
            array('a', false, 'a'),
            array('a', 123, 'a'),
            array('a', array(), 'a'),

            // signature
            array('a', 'a', ''),
            array('a', 'a', null),
            array('a', 'a', true),
            array('a', 'a', false),
            array('a', 'a', 123),
            array('a', 'a', array()),
        );
    }
}
