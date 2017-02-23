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
 * Interface ProductInterface
 * @package Verifone\Core\DependencyInjection\Service\Interfaces
 * A value object interface containing product information
 */
interface Product
{
    public function __construct($name, $unitPriceExclTax, $priceExclTax, $priceInclTax, $quantity, $discountPercentage);

    public function getName();

    public function getUnitPriceExclTax();

    public function getPriceExclTax();

    public function getPriceInclTax();

    public function getQuantity();

    public function getDiscountPercentage();
}
