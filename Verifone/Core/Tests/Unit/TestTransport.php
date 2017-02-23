<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit;


use Verifone\Core\DependencyInjection\Transporter\TransportationWrapper;
use Verifone\Core\Transport\Transport;

class TestTransport implements Transport
{
    private $data;
    
    public function __construct(TransportationWrapper $transport)
    {
        
    }
    
    public function changeDefaultConfiguration($config)
    {
        // TODO: Implement changeDefaultConfiguration() method.
    }
    
    public function close()
    {
        return $this->data;
    }
    
    public function request($url, $data)
    {
        $this->data = $data;
        return false;
    }
}
