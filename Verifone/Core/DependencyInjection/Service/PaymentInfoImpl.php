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
    // user will have radio buttons from which to decide whether to save credit card or not
    const SAVE_METHOD_NORMAL = '0';
    // card is saved automatically during payment
    const SAVE_METHOD_AUTO_SAVE = '1';
    // card is saved without charging anything from it (no payment)
    const SAVE_METHOD_SAVE_ONLY = '2';
    // card is not saved during payment
    const SAVE_METHOD_AUTO_NO_SAVE = '3';

    private $locale;
    private $saveMethod;
    private $savedMethodId;
    private $recurring;
    private $saveMaskedPan;
    private $note;

    /**
     * PaymentInfoImpl constructor.
     * @param string $locale string between 2 and 5 characters
     * @param string $saveMethod numeric one of 0, 1, 2 or 3
     * @param string $savedMethodId
     * @param string $note optional string, max 36 characters, will be cut if too long
     * @param bool $saveMaskedPan
     * @param Recurring|boolean|null $recurring Recurring object to create new subscription, true to indicate recurring payment without subscription details, or null if not a recurring payment
     */
    public function __construct(
        $locale,
        $saveMethod,
        $savedMethodId = '',
        $note = '',
        $saveMaskedPan = false,
        $recurring = null
    ) {
        $this->locale = $locale;
        $this->saveMethod = $saveMethod;
        $this->savedMethodId = $savedMethodId;
        $this->recurring = $recurring;
        $this->saveMaskedPan = $saveMaskedPan;
        $this->note = $note;
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

    public function getSaveMaskedPan()
    {
        return $this->saveMaskedPan;
    }

    public function getNote()
    {
        return $this->note;
    }
}
