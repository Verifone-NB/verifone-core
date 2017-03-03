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

use Verifone\Core\DependencyInjection\Configuration\Configuration;
/**
 * A valua object interface for containing front end configuration information
 * Interface FrontendConfiguration
 * @package Verifone\Core\DependencyInjection\Configuration
 */
interface FrontendConfiguration extends Configuration
{
    public function __construct(
        RedirectUrls $redirectUrls,
        $privateKey,
        $merchantAgreementCode,
        $software,
        $softwareVersion,
        $skipConfirmation
    );

    public function getRedirectUrls();
    
    public function getSkipConfirmation();
}
