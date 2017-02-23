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

use Verifone\Core\DependencyInjection\Service\Interfaces\Product;

/**
 * Class Product
 * @package Verifone\Core\DependencyInjection\Service
 * A value object containing product information
 */
class ProductImpl implements Product
{
    private $name;
    private $unitPriceExclTax;
    private $priceExclTax;
    private $priceInclTax;
    private $quantity;
    private $discountPercentage;

    /**
     * Product constructor.
     * sets product information
     * @param $name string between 1 and 30 characters, can be empty
     * @param $unitPriceExclTax string numeric string between 1 and 20 characters, can be empty
     * @param $priceExclTax string numeric string between 1 and 20 characters, can be empty
     * @param $priceInclTax string numeric string between 1 and 20 characters, can be empty
     * @param $quantity string numberic string between 1 and 11 characters, can be empty
     * @param $discountPercentage string numeric string between 1 and 4 characters, can be empty
     */
    public function __construct($name, $unitPriceExclTax, $priceExclTax, $priceInclTax, $quantity, $discountPercentage)
    {
        $this->name = $name;
        $this->unitPriceExclTax = $unitPriceExclTax;
        $this->priceExclTax = $priceExclTax;
        $this->priceInclTax = $priceInclTax;
        $this->quantity = $quantity;
        $this->discountPercentage = $discountPercentage;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUnitPriceExclTax()
    {
        return $this->unitPriceExclTax;
    }

    public function getPriceExclTax()
    {
        return $this->priceExclTax;
    }

    public function getPriceInclTax()
    {
        return $this->priceInclTax;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getDiscountPercentage()
    {
        return $this->discountPercentage;
    }
}
