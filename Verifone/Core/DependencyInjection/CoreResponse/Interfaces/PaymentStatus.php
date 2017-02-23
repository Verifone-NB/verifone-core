<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\CoreResponse\Interfaces;

interface PaymentStatus
{
    const COMMITTED = 'committed';
    const SETTLED = 'settled';
    const VERIFIED = 'verified';
    const REFUNDED = 'refunded';
    const AUTHORIZED = 'authorized';
    const CANCELLED = 'cancelled';
    const SUBSCRIBED = 'subscribed';
    const INITIATED = 'initiated';
    
    public function __construct($code);

    public function getCode();
}
