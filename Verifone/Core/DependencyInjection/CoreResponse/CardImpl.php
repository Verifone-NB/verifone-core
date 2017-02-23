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
    
    public function __construct($code, $id, $title, $validity)
    {
        $this->code = $code;
        $this->id = $id;
        $this->title = $title;
        $this->validity = $validity;
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
}
