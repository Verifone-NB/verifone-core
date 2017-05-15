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

/**
 * Interface CryptUtil
 * Verifone core specific cryptography -related functionality.
 * @package Verifone\Core\DependencyInjection\CryptUtils
 */
interface CryptUtil
{
    /**
     * Generate signature using RSA with SHA1
     * @param $privateKey string contents of a private key to sign with
     * @param $fields array of fields to generate signature one from
     * @return string signature one generated from fields
     */
    public function generateSignatureOne($privateKey, $fields);

    /**
     * Generate signature using RSA with SHA512
     * @param $privateKey string contents of a private key to sign with
     * @param $fields array of fields to generate signature one from
     * @return string signature one generated from fields
     */
    public function generateSignatureTwo($privateKey, $fields);

    /**
     * @param $publicKey string public key contents
     * @param $fields array data to verify signature for
     * @return bool true - signature valid, false - invalid
     */
    public function verifyResponseFieldsSignature($publicKey, $fields);
}
