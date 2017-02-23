<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Configuration\Backend;

use Verifone\Core\DependencyInjection\Configuration\ConfigurationImpl;

/**
 * A value object containing back end configuration stuff
 * Class BackendConfigurationImpl
 * @package Verifone\Core\DependencyInjection\Service
 */
class BackendConfigurationImpl extends ConfigurationImpl implements BackendConfiguration
{
    private $urls;
    
    public function __construct($privateKey, $merchantAgreementCode, $software, $softwareVersion, array $urls)
    {
        parent::__construct($privateKey, $merchantAgreementCode, $software, $softwareVersion);
        $this->urls = $urls;
    }
    
    public function getUrls()
    {
        return $this->urls;
    }
}
