<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Configuration\Frontend;

use Verifone\Core\DependencyInjection\Configuration\ConfigurationImpl;

/**
 * A value object containing front end configuration information
 * Class FrontendConfigurationImpl
 * @package Verifone\Core\DependencyInjection\Service
 */
class FrontendConfigurationImpl extends ConfigurationImpl implements FrontendConfiguration
{
    private $redirectUrls;
    private $skipConfirmation;
    private $styleCode;

    /**
     * FrontendConfigurationImpl constructor.
     * sets front end configuration information
     * @param RedirectUrls $redirectUrls containing redirect urls
     * @param $privateKey string between 1 and 1000 characters
     * @param $merchantAgreementCode string between 1 and 36 characters
     * @param $software string between 1 and 30 characters
     * @param $softwareVersion string between 1 and 10 characters
     * @param string $skipConfirmation possible values 1 or 0.
     * @param bool $disableRsaBlinding defaults to false
     * @param string $styleCode style code approved by Verifone, defaults to empty
     */
    public function __construct(
        RedirectUrls $redirectUrls,
        $privateKey,
        $merchantAgreementCode,
        $software,
        $softwareVersion,
        $skipConfirmation,
        $disableRsaBlinding = false,
        $styleCode = ''
    ) {
        parent::__construct($privateKey, $merchantAgreementCode, $software, $softwareVersion, $disableRsaBlinding);
        $this->redirectUrls = $redirectUrls;
        $this->skipConfirmation = $skipConfirmation;
        $this->styleCode = $styleCode;
    }

    public function getRedirectUrls()
    {
        return $this->redirectUrls;
    }

    public function getSkipConfirmation()
    {
        return $this->skipConfirmation;
    }

    public function getStyleCode()
    {
        return $this->styleCode;
    }
}
