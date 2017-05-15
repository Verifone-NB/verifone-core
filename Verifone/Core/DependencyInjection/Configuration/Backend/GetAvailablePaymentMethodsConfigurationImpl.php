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

class GetAvailablePaymentMethodsConfigurationImpl extends BackendConfigurationImpl implements GetAvailablePaymentMethodsConfiguration
{
    private $currency;

    /**
     * GetAvailablePaymentMethodsConfigurationImpl constructor.
     * @param $privateKey string between 1 and 1000 characters
     * @param $merchantAgreementCode string between 1 and 36 characters
     * @param $software string between 1 and 30 characters
     * @param $softwareVersion string between 1 and 10 characters
     * @param $urls array of at least 1 long
     * @param string $currency numeric between 1 and 3 characters
     * @param bool $disableRsaBlinding whether to disable rsa blinding
     */
    public function __construct(
        $privateKey,
        $merchantAgreementCode,
        $software,
        $softwareVersion,
        $urls,
        $currency,
        $disableRsaBlinding = false
    ) {
        parent::__construct($privateKey, $merchantAgreementCode, $software, $softwareVersion, $urls, $disableRsaBlinding);
        $this->currency = $currency;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
}
