<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Service;

use Verifone\Core\DependencyInjection\Service\ProductImpl;
use Verifone\Core\Exception\FieldValidationFailedException;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class ProductImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Service
 * @codeCoverageIgnore
 */
class ProductImplTest extends VerifoneTest
{
    /**
     * @param $name
     * @param $unitPriceExclTax
     * @param $priceExclTax
     * @param $priceInclTax
     * @param $quantity
     * @param $discountPercentage
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy(
        $name,
        $unitPriceExclTax,
        $priceExclTax,
        $priceInclTax,
        $quantity,
        $discountPercentage
    ) {
        $product = new ProductImpl($name, $unitPriceExclTax, $priceExclTax, $priceInclTax, $quantity, $discountPercentage);
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($unitPriceExclTax, $product->getUnitPriceExclTax());
        $this->assertEquals($priceExclTax, $product->getPriceExclTax());
        $this->assertEquals($priceInclTax, $product->getPriceInclTax());
        $this->assertEquals($quantity, $product->getQuantity());
        $this->assertEquals($discountPercentage, $product->getDiscountPercentage());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('Vihreä omena', '12.2', '12', '0.3', '90', '22'), // common case
        );
    }
}
