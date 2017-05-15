<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Configuration;

class ConfigurationImpl implements Configuration
{
    private $privateKey;
    private $merchantAgreementCode;
    private $software;
    private $softwareVersion;
    private $disableRsaBlinding;


    /**
     * BackendConfigurationImpl constructor.
     * sets configuration information
     * @param $privateKey string private key
     * @param $merchantAgreementCode string between 1 and 36 characters
     * @param $software string between 1 and 30 characters
     * @param $softwareVersion string between 1 and 10 characters
     * @param bool $disableRsaBlinding defaults to false
     */
    public function __construct($privateKey, $merchantAgreementCode, $software, $softwareVersion, $disableRsaBlinding = false)
    {
        $this->privateKey = $privateKey;
        $this->merchantAgreementCode = $merchantAgreementCode;
        $this->software = $software;
        $this->softwareVersion = $softwareVersion;
        $this->disableRsaBlinding = $disableRsaBlinding;
    }
    
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function getMerchantAgreementCode()
    {
        return $this->merchantAgreementCode;
    }

    public function getSoftware()
    {
        return $this->software;
    }

    public function getSoftwareVersion()
    {
        return $this->softwareVersion;
    }
    
    public function getDisableRsaBlinding()
    {
        return $this->disableRsaBlinding;
    }
}
