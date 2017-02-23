<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\Backend;

use Verifone\Core\Service\Service;

/**
 * Interface BackendService
 * @package Verifone\Core\Service\Backend
 * contains everything that is in common across all the backend services
 */
interface BackendService extends Service
{
    public function getUrls();
    
    public function getResponseConverter();
}
