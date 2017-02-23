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
 * Interface Cryptography
 * @package Verifone\Core\DependencyInjection\CryptUtils
 * Interface to cryptography library
 */
interface Cryptography
{
    /**
     * @param $data string to sign with private key
     * @param $privateKey string to sign the data with
     * @return string the signature
     * @throws CryptUtilException if parameters are not string or are empty
     */
    public function sign($data, $privateKey);

    /**
     * @param $key string to verify the signature with
     * @param $dataToVerify string data to verify signature for
     * @param $signatureData string signature data as hex string
     * @return bool true if signature valid, false if invalid
     * @throws CryptUtilException if parameters not string or are empty
     */
    public function verify($key, $dataToVerify, $signatureData);
}
