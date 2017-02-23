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

/**
 * Class FrontendConfigurationImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Configuration
 * @codeCoverageIgnore
 */
class FrontendConfigurationImplTest extends \PHPUnit_Framework_TestCase
{
    protected $urls;

    public function setUp()
    {
        $this->urls = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrls')
            ->getMock();
    }
    /**
     * @param $privateKey
     * @param $merchantAgreementCode
     * @param $software
     * @param $softwareVersion
     * @param $urls
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($privateKey, $merchantAgreementCode, $software, $softwareVersion)
    {
        $frontendConfiguration = new FrontendConfigurationImpl(
            $this->urls,
            $privateKey,
            $merchantAgreementCode,
            $software,
            $softwareVersion
        );

        $this->assertEquals($privateKey, $frontendConfiguration->getPrivateKey());
        $this->assertEquals($merchantAgreementCode, $frontendConfiguration->getMerchantAgreementCode());
        $this->assertEquals($software, $frontendConfiguration->getSoftware());
        $this->assertEquals($softwareVersion, $frontendConfiguration->getSoftwareVersion());
        $this->assertTrue($frontendConfiguration->getRedirectUrls() instanceof RedirectUrls);
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('aaaaasfwerthieng.sdvcuhbaewjrnwe/f7wer17/-afdnsflkawe7asfd/fansdfnnn_fef78238r7000.fahha&fasdf0s',
                '123asdfas',
                'Magento Example',
                '1.0.2'
            ), // common case
        );
    }

    public function testConstructSadRedirectUrls() {
        $this->expectException(\TypeError::class);
        new FrontendConfigurationImpl(null, '', '', 'a', 'a');
    }
}
