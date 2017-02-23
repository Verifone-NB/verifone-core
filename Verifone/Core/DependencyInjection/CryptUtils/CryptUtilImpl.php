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

use Verifone\Core\Exception\CryptUtilException;

/**
 * Class CryptUtilImpl
 * Verifone core specific cryptography -related functionality.
 * @package Verifone\Core\DependencyInjection\CryptUtils
 */
class CryptUtilImpl implements CryptUtil
{
    const SIG_ONE = 's-t-256-256_signature-one';
    const SIG_TWO = 's-t-256-256_signature-two';


    private $cryptography;

    /**
     * CryptUtilImpl constructor.
     * @param Cryptography $cryptography interface to cryptography lib implementation
     */
    public function __construct(Cryptography $cryptography)
    {
        $this->cryptography = $cryptography;
    }

    /**
     * @param $privateKey string contents of a private key to sign with
     * @param $fields array of fields to generate signature one from
     * @return string signature one generated from fields
     */
    public function generateSignatureOne($privateKey, $fields)
    {
        $this->validateSignatureOneParameters($privateKey, $fields);
        $data = $this->formatFieldsForSignature($fields);
        $signature = $this->cryptography->sign($data, $privateKey);
        return strtoupper(bin2hex($signature));
    }

    /**
     * @param $publicKey string public key contents
     * @param $fields string data to verify signature for
     * @return bool true - signature valid, false - invalid
     */
    public function verifyResponseFieldsSignature($publicKey, $fields)
    {
        $this->validateVerifySignatureParameters($publicKey, $fields);
        $sigOne = $fields[self::SIG_ONE];
        unset($fields[self::SIG_ONE]);
        unset($fields[self::SIG_TWO]);
        $data = $this->formatFieldsForSignature($fields);
        return $this->cryptography->verify($publicKey, $data, $sigOne);
    }

    /**
     * @param $fields array of fields to format for signing
     * @return string of fields as string for signing
     */
    private function formatFieldsForSignature($fields)
    {
        ksort($fields, SORT_STRING);
        $result = '';
        foreach ($fields as $key => $value) {
            $value = str_replace(';', ';;', $value);
            $result .= sprintf("%s=%s;", $key, $value);
        }
        return $result;
    }

    /**
     * @param $privateKey string and not empty
     * @param $fields array
     * @throws CryptUtilException if validation failed
     */
    private function validateSignatureOneParameters($privateKey, $fields)
    {
        if (is_array($fields) === false) {
            throw new CryptUtilException('Tried to generate signature one but parameter fields is not an array');
        }
        if (is_string($privateKey) === false || $privateKey === '') {
            throw new CryptUtilException('Tried to generate signature one but private key contents either is not string or is empty');
        }
    }

    /**
     * @param $publicKey string and not empty
     * @param $fields array
     * @throws CryptUtilException if validation failed
     */
    private function validateVerifySignatureParameters($publicKey, $fields)
    {
        if (is_string($publicKey) === false || $publicKey === '') {
            throw new CryptUtilException('Tried to verify signature but key is not string or is empty');
        }
        if (is_array($fields) === false) {
            throw new CryptUtilException('Tried to verify signature but parameter fields is not an array');
        }
        if (!isset($fields[self::SIG_ONE]) || !is_string($fields[self::SIG_ONE]) || $fields[self::SIG_ONE] === '') {
            throw new CryptUtilException('Tried to verify signature but signature data is not string or is empty');
        }
    }
}
