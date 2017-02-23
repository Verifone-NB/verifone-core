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

    /**
     * FrontendConfigurationImpl constructor.
     * sets front end configuration information
     * @param RedirectUrls $redirectUrls containing redirect urls
     * @param $privateKey string between 1 and 1000 characters
     * @param $merchantAgreementCode string between 1 and 36 characters
     * @param $software string between 1 and 30 characters
     * @param $softwareVersion string between 1 and 10 characters
     */
    public function __construct(
        RedirectUrls $redirectUrls,
        $privateKey,
        $merchantAgreementCode,
        $software,
        $softwareVersion
    ) {
        parent::__construct($privateKey, $merchantAgreementCode, $software, $softwareVersion);
        $this->redirectUrls = $redirectUrls;
    }

    public function getRedirectUrls()
    {
        return $this->redirectUrls;
    }
}
