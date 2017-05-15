<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\Frontend;


use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\Service\Service;
use Verifone\Core\Storage\Storage;

/**
 * Interface FrontendService
 * @package Verifone\Core\Service\Frontend
 * The purpose of this class is to contain frontend (Verifone hosted) information needed for creating the request
 */
interface FrontendService extends Service
{
    /**
     * FrontendService constructor.
     * @param Storage $storage for containing the actual fields
     * @param FrontendConfiguration $frontEndConfiguration fot the configuration values
     * @param CryptUtil $crypto for calculating signatures
     */
    public function __construct(Storage $storage, FrontendConfiguration $frontEndConfiguration, CryptUtil $crypto);
}
