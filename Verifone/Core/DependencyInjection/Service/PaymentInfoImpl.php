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


use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Recurring;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;

class PaymentInfoImpl implements PaymentInfo
{
    private $locale;
    private $saveMethod;
    private $savedMethodId;
    private $recurring;

    /**
     * PaymentInfoImpl constructor.
     * @param string $locale string between 2 and 5 characters
     * @param string $saveMethod numeric one of 0, 1, 2 or 3
     * @param string $savedMethodId
     * @param Recurring $recurring
     */
    public function __construct($locale, $saveMethod, $savedMethodId = '', Recurring $recurring = null
    ) {
        $this->locale = $locale;
        $this->saveMethod = $saveMethod;
        $this->savedMethodId = $savedMethodId;
        $this->recurring = $recurring;
    }
    
    public function getLocale()
    {
        return $this->locale;
    }
    
    public function getSaveMethod()
    {
        return $this->saveMethod;
    }
    
    public function getSavedMethodId()
    {
        return $this->savedMethodId;
    }

    public function getRecurring()
    {
        return $this->recurring;
    }

}
