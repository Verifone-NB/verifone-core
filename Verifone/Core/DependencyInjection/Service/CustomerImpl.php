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

use Verifone\Core\DependencyInjection\Service\Interfaces\Address;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;

/**
 * Class Customer
 * @package Verifone\Core\DependencyInjection\Service
 * A value object containing customer information
 */
class CustomerImpl implements Customer
{
    private $firstName;
    private $lastName;
    private $phoneNumber;
    private $email;
    private $address;

    /**
     * Customer constructor.
     * sets customer information
     * @param $firstName string between 1 and 30 characters
     * @param $lastName string between 1 and 30 characters
     * @param $phoneNumber string between 1 and 30 characters, can be empty
     * @param $email string between 1 and 100 characters
     * @param $address Address optional
     */
    public function __construct($firstName, $lastName, $phoneNumber, $email, Address $address = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->address = $address;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function getAddress()
    {
        return $this->address;
    }
}
