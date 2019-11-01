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

use Verifone\Core\DependencyInjection\Service\CustomerImpl;
use Verifone\Core\DependencyInjection\Service\AddressImpl;
use Verifone\Core\Tests\Unit\VerifoneTest;

/**
 * Class CustomerImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Service
 */
class CustomerImplTest extends VerifoneTest
{
    /**
     * @param $firstName
     * @param $lastName
     * @param $phoneNumber
     * @param $email
     * @param $address
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($firstName, $lastName, $phoneNumber, $email, $address, $externalId = '')
    {
        $customer = new CustomerImpl($firstName, $lastName, $phoneNumber, $email, $address, $externalId);
        $this->assertEquals($firstName, $customer->getFirstName());
        $this->assertEquals($lastName, $customer->getLastName());
        $this->assertEquals($phoneNumber, $customer->getPhoneNumber());
        $this->assertEquals($email, $customer->getEmail());
        $this->assertEquals($address, $customer->getAddress());
        $this->assertEquals($externalId, $customer->getExternalId());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('Testi', 'Testinen', '040-1234567', 'a@a.com', new AddressImpl('a', 'a', 'aa', 'a', 'a', 'a', 'a', 'b')),
            array('T', 'T', '1', 'a', new AddressImpl('a', 'a', 'aa', 'a', 'a', 'a', 'a', 'b'), 'a'),
            array('T', 'T', '1', 'a', new AddressImpl('a', 'a', 'aa', 'a', 'a', 'a', 'a', 'b')),
        );
    }
}
