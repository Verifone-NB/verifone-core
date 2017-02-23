<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\FrontendResponse;


use Verifone\Core\Service\Service;

interface FrontendResponseService extends Service
{
    public function getResponse();
    
    public function getOrderNumber();
}
