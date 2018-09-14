<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2018 Lamia Oy (https://lamia.fi)
 */


namespace Verifone\Core\DependencyInjection\CryptUtils;

/**
 * Interface KeyGenerator
 * Verifone core specific key generator  -related functionality.
 * @package Verifone\Core\DependencyInjection\CryptUtils
 */
interface KeyGenerator
{

    /**
     * Method for generate keys
     * @return bool true if generated correctly, false otherwise
     */
    public function generate();

    /**
     * @return string generated public key
     */
    public function getPublicKey();

    /**
     * @return string generated private key
     */
    public function getPrivateKey();
}