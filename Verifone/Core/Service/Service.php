<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service;


use Verifone\Core\DependencyInjection\Service\Interfaces\Customer;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\DependencyInjection\Service\Interfaces\PaymentInfo;
use Verifone\Core\DependencyInjection\Service\Interfaces\Product;
use Verifone\Core\DependencyInjection\Service\Interfaces\Transaction;

/**
 * Interface Service
 * @package Verifone\Core\Service
 * Contains the information needed for the verifone request.
 */
interface Service
{
    /**
     * @param Customer $customer
     * Insert customer information
     */
    public function insertCustomer(Customer $customer);

    /**
     * @param Product $product
     * Insert product, typically inserting multiple is possible.
     */
    public function insertProduct(Product $product);

    /**
     * @param Order $order
     * Insert general order information
     */
    public function insertOrder(Order $order);

    /**
     * @param PaymentInfo $paymentInfo
     * Insert payment information
     */
    public function insertPaymentInfo(PaymentInfo $paymentInfo);

    /**
     * @param Transaction $transaction
     * Insert transaction information
     */
    public function insertTransaction(Transaction $transaction);

    /**
     * @return mixed verifone fields contained in the service.
     */
    public function getFields();
}
