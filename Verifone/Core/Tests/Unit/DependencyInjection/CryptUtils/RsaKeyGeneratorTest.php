<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright Copyright (c) 2018 Lamia Oy (https://lamia.fi)
 */


namespace Verifone\Core\Tests\Unit\DependencyInjection\CryptUtils;


use Verifone\Core\DependencyInjection\CryptUtils\RsaKeyGenerator;
use Verifone\Core\Tests\Unit\VerifoneTest;

class RsaKeyGeneratorTest extends VerifoneTest
{

    /**
     * @group KeyGenerator
     */
    public function testGenerateKeys()
    {
        $generator = new RsaKeyGenerator();
        $result = $generator->generate();

        $this->assertTrue($result);
        $this->assertNotEmpty($generator->getPrivateKey(), 'Private key not generated');
        $this->assertNotEmpty($generator->getPublicKey(), 'Public key not generated');
    }
}