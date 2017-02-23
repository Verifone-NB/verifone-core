<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Service\Interfaces;

interface PaymentInfo
{
    public function __construct($locale, $saveMethod, $savedMethodIdId, Recurring $recurring);
    
    public function getLocale();
    
    public function getSaveMethod();
    
    public function getSavedMethodId();
    
    public function getRecurring();
}
