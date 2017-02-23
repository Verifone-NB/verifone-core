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

use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;
use Verifone\Core\Service\Service;
use Verifone\Core\Storage\Storage;

class FrontendResponseServiceImpl implements FrontendResponseService
{
    private $storage;
    private $response;

    public function __construct(Storage $storage, array $response)
    {
        $this->storage = $storage;
    }

    public function getFields()
    {
        return $this->storage;
    }

    public function getResponse()
    {
        $this->response;
    }

    public function getOrderNumber()
    {
        return $this->response[FieldConfig::ORDER_NUMBER];
    }

    public function insertOrder(Order $order)
    {
        $this->storage->add(FieldConfig::ORDER_TIMESTAMP, $order->getTimestamp());
        $this->storage->add(FieldConfig::ORDER_NUMBER, $order->getIdentificator());
        $this->storage->add(FieldConfig::ORDER_TOTAL_INCL_TAX, $order->getTotalInclTax());
        $this->storage->add(FieldConfig::ORDER_CURRENCY, $order->getCurrency());
    }

    public function insertPaymentInfo(PaymentInfo $paymentInfo)
    {
        // DOES NOTHING
    }

    public function insertProduct(Product $product)
    {
        // DOES NOTHING
    }

    public function insertTransaction(Transaction $transaction)
    {
        // DOES NOTHING
    }

    public function insertCustomer(Customer $customer)
    {
        // DOES NOTHING
    }
}
