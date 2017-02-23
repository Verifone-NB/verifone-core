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

/**
 * Interface OrderInterface
 * @package Verifone\Core\DependencyInjection\Service\Interfaces
 * A value object interface containing order information
 */
interface Order
{
    public function __construct(
        $identificator,
        $timestamp,
        $currency,
        $totalInclTax,
        $totalExclTax,
        $taxAmount
    );

    public function getIdentificator();

    public function getTimestamp();

    public function getCurrency();

    public function getTotalInclTax();

    public function getTotalExclTax();

    public function getTaxAmount();
}
