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

namespace Verifone\Core\DependencyInjection\CoreResponse;

use Verifone\Core\DependencyInjection\CoreResponse\Interfaces\PaymentStatus;

class PaymentStatusImpl implements PaymentStatus
{
    private $code;

    private $orderAmount;

    private $transactionNumber;

    private $paymentMethodCode;

    private $orderNumber;

    private $orderTimestamp;

    public function __construct(
        $code,
        $orderAmount,
        $transactionNumber,
        $paymentMethodCode,
        $orderNumber,
        $orderTimestamp
    )
    {
        $this->code = $code;
        $this->orderAmount = $orderAmount;
        $this->transactionNumber = $transactionNumber;
        $this->paymentMethodCode = $paymentMethodCode;
        $this->orderNumber = $orderNumber;
        $this->orderTimestamp = $orderTimestamp;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    /**
     * @return mixed
     */
    public function getTransactionNumber()
    {
        return $this->transactionNumber;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethodCode()
    {
        return $this->paymentMethodCode;
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @return mixed
     */
    public function getOrderTimestamp()
    {
        return $this->orderTimestamp;
    }

}
