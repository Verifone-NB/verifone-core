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

interface Configuration
{
    public function getPrivateKey();

    public function getMerchantAgreementCode();

    public function getSoftware();

    public function getSoftwareVersion();
    
    public function getDisableRsaBlinding();
}
