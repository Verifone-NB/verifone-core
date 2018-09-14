<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2018 Lamia Oy (https://lamia.fi)
 */


namespace Verifone\Core\DependencyInjection\CryptUtils;


use phpseclib\Crypt\RSA;

class RsaKeyGenerator extends AbstractKeyGenerator
{

    const BITS = 1024;

    /**
     * @inheritdoc
     */
    public function generate()
    {
        try {
            $rsa = new RSA();
            $keyPair = $rsa->createKey(self::BITS);

            $this->setKeys($keyPair['publickey'], $keyPair['privatekey']);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}