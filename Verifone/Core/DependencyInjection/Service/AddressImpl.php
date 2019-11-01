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

class AddressImpl implements Address
{
    private $lineOne;
    private $lineTwo;
    private $lineThree;
    private $city;
    private $postalCode;
    private $countryCode;
    private $firstName;
    private $lastName;
    private $phoneNumber;
    private $email;
    
    public function __construct($lineOne, $lineTwo, $lineThree, $city, $postalCode, $countryCode, $firstName, $lastName, $phoneNumber = '', $email = '')
    {
        $this->lineOne = $lineOne;
        $this->lineTwo = $lineTwo;
        $this->lineThree = $lineThree;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }
    
    public function getLineOne()
    {
        return $this->lineOne;
    }
    
    public function getLineTwo()
    {
        return $this->lineTwo;
    }
    
    public function getLineThree()
    {
        return $this->lineThree;
    }
    
    public function getCity()
    {
        return $this->city;
    }
    
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    
    public function getCountryCode()
    {
        return $this->countryCode;
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


}
