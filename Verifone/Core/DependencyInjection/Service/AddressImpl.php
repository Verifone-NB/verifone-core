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
    
    public function __construct($lineOne, $lineTwo, $lineThree, $city, $postalCode, $countryCode)
    {
        $this->lineOne = $lineOne;
        $this->lineTwo = $lineTwo;
        $this->lineThree = $lineThree;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
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
}
