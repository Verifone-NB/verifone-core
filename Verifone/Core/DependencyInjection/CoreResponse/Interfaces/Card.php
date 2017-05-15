<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\CoreResponse\Interfaces;

interface Card
{
    public function __construct($code, $id, $title, $validity, $first6, $last2);
    
    public function getCode();
    
    public function getId();
    
    public function getTitle();
    
    public function getValidity();
    
    public function getFirst6();
    
    public function getLast2();
}
