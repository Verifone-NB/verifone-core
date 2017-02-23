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


use Verifone\Core\DependencyInjection\Service\AddressImpl;

class AddressImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $lineONe
     * @param $lineTwo
     * @param $lineThree
     * @param $city
     * @param $postal
     * @param $country
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($lineONe, $lineTwo, $lineThree, $city, $postal, $country)
    {
        $address = new AddressImpl($lineONe, $lineTwo, $lineThree, $city, $postal, $country);
        $this->assertEquals($lineONe, $address->getLineOne());
        $this->assertEquals($lineTwo, $address->getLineTwo());
        $this->assertEquals($lineThree, $address->getLineThree());
        $this->assertEquals($city, $address->getCity());
        $this->assertEquals($postal, $address->getPostalCode());
        $this->assertEquals($country, $address->getCountryCode());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'a', 'aa', 'a', 'a', 'a'),
            array('', '', '', '', '', ''),
        );
    }
}
