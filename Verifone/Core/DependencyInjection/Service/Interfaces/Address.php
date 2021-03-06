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
 * Interface AddressInterface
 * @package Verifone\Core\DependencyInjection\Service\Interfaces
 * A value object interface for containing address information
 */
interface Address
{
    public function __construct($lineOne, $lineTwo, $lineThree, $city, $postalCode, $countryCode, $fistName, $lastName, $phoneNumber = '', $email = '');
    
    public function getLineOne();
    
    public function getLineTwo();
    
    public function getLineThree();
    
    public function getCity();
    
    public function getPostalCode();
    
    public function getCountryCode();

    public function getFirstName();

    public function getLastName();

    public function getPhoneNumber();

    public function getEmail();
}
