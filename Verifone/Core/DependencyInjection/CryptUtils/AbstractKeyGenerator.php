<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2018 Lamia Oy (https://lamia.fi)
 */


namespace Verifone\Core\DependencyInjection\CryptUtils;


abstract class AbstractKeyGenerator implements KeyGenerator
{
    /**
     * @var string Public Key
     */
    private $publicKey;

    /**
     * @var string Private Key
     */
    private $privateKey;

    /**
     * @param string $publicKey
     * @param string $privateKey
     */
    protected function setKeys($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @inheritdoc
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @inheritdoc
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

}