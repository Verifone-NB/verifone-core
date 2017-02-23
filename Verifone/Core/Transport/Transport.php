<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Transport;

use Verifone\Core\DependencyInjection\Transporter\TransportationWrapper;

interface Transport
{
    public function __construct(TransportationWrapper $transport);
    
    public function changeDefaultConfiguration($config);

    public function request($url, $data);
    
    public function close();
}
