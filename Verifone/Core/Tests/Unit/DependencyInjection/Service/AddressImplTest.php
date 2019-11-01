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
use Verifone\Core\Tests\Unit\VerifoneTest;

class AddressImplTest extends VerifoneTest
{
    /**
     * @param $lineONe
     * @param $lineTwo
     * @param $lineThree
     * @param $city
     * @param $postal
     * @param $country
     * @param $firstName
     * @param $lastName
     * @param string $phoneNumber
     * @param string $email
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($lineONe, $lineTwo, $lineThree, $city, $postal, $country, $firstName, $lastName, $phoneNumber = '', $email = '')
    {
        $address = new AddressImpl($lineONe, $lineTwo, $lineThree, $city, $postal, $country, $firstName, $lastName, $phoneNumber, $email);
        $this->assertEquals($lineONe, $address->getLineOne());
        $this->assertEquals($lineTwo, $address->getLineTwo());
        $this->assertEquals($lineThree, $address->getLineThree());
        $this->assertEquals($city, $address->getCity());
        $this->assertEquals($postal, $address->getPostalCode());
        $this->assertEquals($country, $address->getCountryCode());
        $this->assertEquals($firstName, $address->getFirstName());
        $this->assertEquals($lastName, $address->getLastName());
        $this->assertEquals($phoneNumber, $address->getPhoneNumber());
        $this->assertEquals($email, $address->getEmail());

    }

    public function providerTestConstructHappy()
    {
        return array(
            array('a', 'a', 'aa', 'a', 'a', 'a', 'a', 'aa'),
            array('', '', '', '', '', '', '', ''),
        );
    }
}
