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
 * Interface CustomerInterface
 * @package Verifone\Core\DependencyInjection\Service\Interfaces
 * A value object interface for containing customer information
 */
interface Customer
{
    public function __construct($firstName, $lastName, $phoneNumber, $email, Address $address, $externalId);

    public function getFirstName();

    public function getLastName();

    public function getPhoneNumber();

    public function getEmail();

    public function getExternalId();

    public function getAddress();
}
