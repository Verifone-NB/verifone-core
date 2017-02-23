<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Service;


use Verifone\Core\DependencyInjection\Service\Interfaces\Recurring;

class RecurringImpl implements Recurring
{
    private $subscriptionName;
    private $subscriptionCode;
    
    public function __construct($subscriptionName, $subscriptionCode)
    {
        $this->subscriptionName = $subscriptionName;
        $this->subscriptionCode = $subscriptionCode;
    }
    
    public function getSubscriptionName()
    {
        return $this->subscriptionName;
    }
    
    public function getSubscriptionCode()
    {
        return $this->subscriptionCode;
    }
}
