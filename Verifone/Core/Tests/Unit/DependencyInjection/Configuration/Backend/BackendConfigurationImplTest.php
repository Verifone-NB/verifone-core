<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Configuration\Backend;

use Verifone\Core\DependencyInjection\Configuration\Backend\BackendConfigurationImpl;
use Verifone\Core\Exception\FieldValidationFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class BackendConfigurationImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Configuration
 * @codeCoverageIgnore
 */
class BackendConfigurationImplTest extends VerifoneTest
{
    /**
     * @oaram $pKey
     * @param $merchantAgreementCode
     * @param $software
     * @param $softwareVersion
     * @param $urls
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($pKey, $merchantAgreementCode, $software, $softwareVersion, $urls)
    {
        $backendConfiguration = new BackendConfigurationImpl(
            $pKey,
            $merchantAgreementCode,
            $software,
            $softwareVersion,
            $urls
        );
        $this->assertEquals($merchantAgreementCode, $backendConfiguration->getMerchantAgreementCode());
        $this->assertEquals($software, $backendConfiguration->getSoftware());
        $this->assertEquals($softwareVersion, $backendConfiguration->getSoftwareVersion());
        $this->assertEquals($pKey, $backendConfiguration->getPrivateKey());
        $this->assertTrue(is_array($backendConfiguration->getUrls()));
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('aaaaasfwerthieng.sdvcuhbaewjrnwe/f7wer17/-afdnsflkawe7asfd/fansdfnnn_fef78238r7000.',
                '123asdfas',
                'Magento Example',
                '1.0.2',
                array('jee', 'jee2', 'jee3', 'http://test.com/')
            ), // common case
        );
    }

}
