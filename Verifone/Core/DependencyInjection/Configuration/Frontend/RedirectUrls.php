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

/**
 * A valua object interface for containing redirect urls
 * Interface RedirectUrls
 * @package Verifone\Core\DependencyInjection\Configuration
 */
interface RedirectUrls
{
    public function __construct($success, $rejected, $cancel, $expired, $error);

    public function getSuccessUrl();

    public function getRejectedUrl();
    
    public function getCancelUrl();
    
    public function getExpiredUrl();
    
    public function getErrorUrl();
}
