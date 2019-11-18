<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Configuration\Frontend;


use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfigurationImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrls;
use Verifone\Core\Exception\FieldValidationFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class FrontendConfigurationImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Configuration
 * @codeCoverageIgnore
 */
class FrontendConfigurationImplTest extends VerifoneTest
{
    protected $urls;

    public function setUp(): void
    {
        $this->urls = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrls')
            ->getMock();
    }
    /**
     * @param $privateKey
     * @param $merchantAgreementCode
     * @param $software
     * @param $softwareVersion
     * @param $skipConfirm
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($privateKey, $merchantAgreementCode, $software, $softwareVersion, $skipConfirm)
    {
        $frontendConfiguration = new FrontendConfigurationImpl(
            $this->urls,
            $privateKey,
            $merchantAgreementCode,
            $software,
            $softwareVersion,
            $skipConfirm
        );

        $this->assertEquals($privateKey, $frontendConfiguration->getPrivateKey());
        $this->assertEquals($merchantAgreementCode, $frontendConfiguration->getMerchantAgreementCode());
        $this->assertEquals($software, $frontendConfiguration->getSoftware());
        $this->assertEquals($softwareVersion, $frontendConfiguration->getSoftwareVersion());
        $this->assertTrue($frontendConfiguration->getRedirectUrls() instanceof RedirectUrls);
        $this->assertEquals($skipConfirm, $frontendConfiguration->getSkipConfirmation());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('aaaaasfwerthieng.sdvcuhbaewjrnwe/f7wer17/-afdnsflkawe7asfd/fansdfnnn_fef78238r7000.fahha&fasdf0s',
                '123asdfas',
                'Magento Example',
                '1.0.2',
                '1'
            ), // common case
            array('aaaaasfwerthieng.sdvcuhbaewjrnwe/f7wer17/-afdnsflkawe7asfd/fansdfnnn_fef78238r7000.fahha&fasdf0s',
                '123asdfas',
                'Magento Example',
                '1.0.2',
                '1',
                false,
                'test-style-code'
            ),
        );
    }

    public function testConstructSadRedirectUrls() {
        $this->expectException(\TypeError::class);
        new FrontendConfigurationImpl(null, '', '', 'a', 'a', '1');
    }
}
