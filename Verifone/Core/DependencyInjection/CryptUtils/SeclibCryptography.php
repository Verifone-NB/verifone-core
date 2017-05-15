<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\CryptUtils;


use phpseclib\Crypt\RSA;
use Verifone\Core\Exception\CryptUtilException;

/**
 * Class SecLibCryptography
 * @package Verifone\Core\DependencyInjection\CryptUtils
 * Cryptography interface to PHPSecLib cryptography impelementation
 */
class SeclibCryptography implements Cryptography
{
    /**
     * @param string $data to sign with private key
     * @param string $privateKey to sign the data with
     * @param string $hash algorithm used with the signing
     * @return string the signature
     * @throws CryptUtilException if validation of parameters failed
     */
    public function sign($data, $privateKey, $hash = 'sha1')
    {
        $this->validateSignParameters($data, $privateKey);
        $rsa = new RSA();
        $rsa->setHash($hash);
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
        $rsa->loadKey($privateKey);
        return $rsa->sign($data);
    }

    /**
     * @param $data string and not empty
     * @param $privateKey string and not empty
     * @throws CryptUtilException if validation of parameters failed
     */
    private function validateSignParameters($data, $privateKey)
    {
        if (is_string($privateKey) === false || $privateKey === '') {
            throw new CryptUtilException('Tried to sign data but private key contents either is not string or is empty');
        }
        if (is_string($data) === false || $data === '') {
            throw new CryptUtilException('Tried to sign data but data contents either is not string or is empty');
        }
    }

    /**
     * @param string $key to verify the signature with
     * @param string $dataToVerify data to verify signature for
     * @param string $signatureData signature data as hex string
     * @param string $hash algorithm to use, defaults to sha1
     * @return bool true if signature valid, false if invalid
     * @throws CryptUtilException if validation of parameters failed
     */
    public function verify($key, $dataToVerify, $signatureData, $hash = 'sha1')
    {
        $this->validateVerifyParameters($key, $dataToVerify, $signatureData);
        $rsa = new RSA();
        $rsa->setHash($hash);
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
        $rsa->loadKey($key);
        return $rsa->verify($dataToVerify, pack("H*", $signatureData));
    }

    /**
     * @param $key string and not empty
     * @param $dataToVerify string and not empty
     * @param $signatureData string and not empty
     * @throws CryptUtilException if validation of parameters failed
     */
    private function validateVerifyParameters($key, $dataToVerify, $signatureData)
    {
        if (is_string($key) === false || $key === '') {
            throw new CryptUtilException('Tried to verify signature but key is not string or is empty');
        }
        if (is_string($dataToVerify) === false || $dataToVerify === '') {
            throw new CryptUtilException('Tried to verify signature but data to veify is not string or is empty');
        }
        if (is_string($signatureData) === false || $signatureData === '') {
            throw new CryptUtilException('Tried to verify signature but signature data is not string or is empty');
        }
    }
}
