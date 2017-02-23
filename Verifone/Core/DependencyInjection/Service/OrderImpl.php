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

use Verifone\Core\DependencyInjection\Service\Interfaces\Order;

/**
 * Class Order
 * @package Verifone\Core\DependencyInjection\Service
 * A value object containing order information
 */
class OrderImpl implements Order
{
    const DATEFORMAT = 'Y-m-d H:i:s';
    private $identificator;
    private $timestamp;
    private $currency;
    private $totalInclTax;
    private $totalExclTax;
    private $taxAmount;

    /**
     * Order constructor.
     * sets order information
     * @param $identificator string between 1 and 36 characters, can't be cut
     * @param $timestamp string date in format Y-m-d H:i:s
     * @param $currency string numeric string between 1 and 3 characters, can't be cut
     * @param $totalInclTax string numeric string between 1 and 20 characters, can't be cut
     * @param $totalExclTax string numeric string between 1 and 20 characters, can't be cut
     * @param $taxAmount string numeric string between 1 and 20 characters, can't be cut
     */
    public function __construct(
        $identificator,
        $timestamp,
        $currency,
        $totalInclTax,
        $totalExclTax,
        $taxAmount
    ) {
        $this->identificator = $identificator;
        $this->timestamp = $timestamp;
        $this->currency = $currency;
        $this->totalInclTax = $totalInclTax;
        $this->totalExclTax = $totalExclTax;
        $this->taxAmount = $taxAmount;
    }

    public function getIdentificator()
    {
        return $this->identificator;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getTotalInclTax()
    {
        return $this->totalInclTax;
    }

    public function getTotalExclTax()
    {
        return $this->totalExclTax;
    }

    public function getTaxAmount()
    {
        return $this->taxAmount;
    }
}
