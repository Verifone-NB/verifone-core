<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\Frontend;

use Verifone\Core\Configuration\FieldConfigImpl;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\Storage\Storage;

/**
 * Class AddNewCardService
 * @package Verifone\Core\Service\Frontend
 * The purpose of this class is to contain and generate request fields for adding a new card.
 */
final class AddNewCardService extends AbstractFrontendService
{
    const PRODUCT_NAME_VALUE = 'fake product';
    const PRODUCT_QUANTITY_VALUE = '1';
    const PRODUCT_DISCOUNT_VALUE = '0';
    const PRODUCT_TAX_VALUE = '0';
    const PRODUCT_PRICE_VALUE = '1';
    const ORDER_NUMBER_VALUE = 'addNewCard';
    const SAVE_PAYMENT_METHOD_VALUE = '2';

    /**
     * AddNewCardService constructor.
     * @param Storage $storage
     * @param FrontendConfiguration $frontEndConfiguration
     * @param CryptUtil $crypto
     */
    public function __construct(Storage $storage, FrontendConfiguration $frontEndConfiguration, CryptUtil $crypto)
    {
        parent::__construct($storage, $frontEndConfiguration, $crypto);
        $this->insertFakePaymentInfo();
        $this->insertFakeOrder();
        $this->insertFakeProduct();
    }

    /**
     * @param Order $order
     * Only currency is really needed.
     */
    public function insertOrder(Order $order)
    {
        $this->addToStorage(FieldConfigImpl::ORDER_CURRENCY, $order->getCurrency());
    }

    /**
     * Insert fake payment info, since this request is only made to save the credit card.
     */
    private function insertFakePaymentInfo()
    {
        $this->addToStorage(FieldConfigImpl::PAYMENT_SAVE_METHOD, self::SAVE_PAYMENT_METHOD_VALUE);
    }

    /**
     * Insert fake order information, since this request is only made to save the credit card.
     */
    private function insertFakeOrder()
    {
        $this->addToStorage(FieldConfigImpl::ORDER_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->addToStorage(FieldConfigImpl::ORDER_NUMBER, self::ORDER_NUMBER_VALUE);
        $this->addToStorage(FieldConfigImpl::ORDER_TOTAL_INCL_TAX, self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfigImpl::ORDER_TOTAL_EXCL_TAX, self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfigImpl::ORDER_TAX_AMOUNT, self::PRODUCT_TAX_VALUE);
        $this->addPaymentToken(self::ORDER_NUMBER_VALUE);
    }

    /**
     * Insert fake values for the product, since this request is only done to save the credit card
     */
    private function insertFakeProduct()
    {
        $this->addToStorage(FieldConfigImpl::PRODUCT_NAME . '0', self::PRODUCT_NAME_VALUE);
        $this->addToStorage(FieldConfigImpl::PRODUCT_QUANTITY . '0', self::PRODUCT_QUANTITY_VALUE);
        $this->addToStorage(FieldConfigImpl::PRODUCT_DISCOUNT . '0', self::PRODUCT_DISCOUNT_VALUE);
        $this->addToStorage(FieldConfigImpl::PRODUCT_TAX . '0', self::PRODUCT_TAX_VALUE);
        $this->addToStorage(FieldConfigImpl::PRODUCT_UNIT_PRICE . '0', self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfigImpl::PRODUCT_PRICE_EXCL_TAX . '0', self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfigImpl::PRODUCT_PRICE_INCL_TAX . '0', self::PRODUCT_PRICE_VALUE);
    }
}
