<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\CoreResponse;

use Verifone\Core\DependencyInjection\CoreResponse\Interfaces\Card;

class CardImpl implements Card
{
    private $code;
    private $id;
    private $title;
    private $validity;
    private $first6;
    private $last2;
    
    public function __construct($code, $id, $title, $validity, $first6 = '', $last2 = '')
    {
        $this->code = $code;
        $this->id = $id;
        $this->title = $title;
        $this->validity = $validity;
        $this->first6 = $first6;
        $this->last2 = $last2;
    }
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getValidity()
    {
        return $this->validity;
    }

    public function getFirst6()
    {
        return $this->first6;
    }

    public function getLast2()
    {
        return $this->last2;
    }
}
