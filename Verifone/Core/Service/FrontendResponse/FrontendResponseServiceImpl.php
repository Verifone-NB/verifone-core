<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 * @author     Szymon Nosal <simon@lamia.fi>
 */

namespace Verifone\Core\Service\FrontendResponse;

use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Exception\ResponseCheckFailedException;
use Verifone\Core\Service\AbstractService;
use Verifone\Core\Storage\Storage;

/**
 * Class FrontendResponseServiceImpl
 * @package Verifone\Core\Service\FrontendResponse
 * A service for the Frontend response handling
 *
 * After Verifone hosted service has redirected back to one of the redirect links, the response message that comes
 * with that needs to be validated and converted to common format, which is the job of this class.
 */
class FrontendResponseServiceImpl implements FrontendResponseService
{
    private $storage;
    private $response;

    /**
     * FrontendResponseServiceImpl constructor.
     * @param Storage $storage for storing the fields
     * @param array $response from the verifone
     */
    public function __construct(Storage $storage, array $response)
    {
        $this->storage = $storage;
        $this->response = $response;
    }

    /**
     * @return Storage added information to check the response against
     */
    public function getFields()
    {
        return $this->storage;
    }

    /**
     * @return array verifone response fields
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed order number, null if no order number is set.
     * Can be used to fetch the order information to inject for the validation
     */
    public function getOrderNumber()
    {
        if (isset($this->response[FieldConfigImpl::ORDER_NUMBER])) {
            return $this->response[FieldConfigImpl::ORDER_NUMBER];
        }
        return null;
    }

    /**
     * @param Order $order
     * The values that the response is validated against
     */
    public function insertOrder(Order $order)
    {
        $this->storage->add(FieldConfigImpl::ORDER_TIMESTAMP, $order->getTimestamp());
        $this->storage->add(FieldConfigImpl::ORDER_NUMBER, $order->getIdentificator());
        $this->storage->add(FieldConfigImpl::ORDER_TOTAL_INCL_TAX, $order->getTotalInclTax());
        $this->storage->add(FieldConfigImpl::ORDER_CURRENCY, $order->getCurrency());
    }
}
