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

use Verifone\Core\DependencyInjection\Configuration\Configuration;

/**
 * A valua object interface for containing back end configuration information
 * Interface BackendConfiguration
 * @package Verifone\Core\DependencyInjection\Configuration
 */
interface BackendConfiguration extends Configuration
{
    public function getUrls();
}
