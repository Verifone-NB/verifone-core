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


use Verifone\Core\DependencyInjection\Service\Interfaces\Order;

/**
 * Interface FrontendResponseService
 * @package Verifone\Core\Service\FrontendResponse
 * A service for the Frontend response handling
 * 
 * After Verifone hosted service has redirected back to one of the redirect links, the response message that comes
 * with that needs to be validated and converted to common format, which is the job of this class.
 */
interface FrontendResponseService
{
    /**
     * @return mixed The verifone response associated with the frontend response service
     */
    public function getResponse();

    /**
     * @return mixed order number of the frontend response
     */
    public function getOrderNumber();

    /**
     * @return mixed verifone fields contained in the service.
     */
    public function getFields();

    /**
     * @param Order $order
     * Insert general order information
     */
    public function insertOrder(Order $order);
}
