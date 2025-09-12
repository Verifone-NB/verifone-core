<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 * @author     Szymon Nosal <simon@lamia.fi>
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

    public function __construct(
        $code,
        $orderAmount,
        $transactionNumber,
        $paymentMethodCode,
        $orderNumber,
        $orderTimestamp
    );

    public function getCode();

    public function getOrderAmount();

    public function getTransactionNumber();

    public function getPaymentMethodCode();

    public function getOrderNumber();

    public function getOrderTimestamp();
}
