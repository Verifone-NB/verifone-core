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

use Verifone\Core\Configuration\FieldConfig;
use Verifone\Core\DependencyInjection\Configuration\Frontend\FrontendConfiguration;
use Verifone\Core\DependencyInjection\CryptUtils\CryptUtil;
use Verifone\Core\DependencyInjection\Service\Interfaces\Order;
use Verifone\Core\Storage\Storage;

final class AddNewCardService extends AbstractFrontendService
{
    const PRODUCT_NAME_VALUE = 'fake product';
    const PRODUCT_QUANTITY_VALUE = '1';
    const PRODUCT_DISCOUNT_VALUE = '0';
    const PRODUCT_TAX_VALUE = '0';
    const PRODUCT_PRICE_VALUE = '1';
    const ORDER_NUMBER_VALUE = 'addNewCard';
    const SAVE_PAYMENT_METHOD_VALUE = '2';

    public function __construct(Storage $storage, FrontendConfiguration $frontEndConfiguration, CryptUtil $crypto)
    {
        parent::__construct($storage, $frontEndConfiguration, $crypto);
        $this->insertFakePaymentInfo();
        $this->insertFakeOrder();
        $this->insertFakeProduct();
    }
    
    public function insertOrder(Order $order)
    {
        $this->addToStorage(FieldConfig::ORDER_CURRENCY, $order->getCurrency());
    }
    
    private function insertFakePaymentInfo()
    {
        $this->addToStorage(FieldConfig::PAYMENT_SAVE_METHOD, self::SAVE_PAYMENT_METHOD_VALUE);
    }

    private function insertFakeOrder()
    {
        $this->addToStorage(FieldConfig::ORDER_TIMESTAMP, gmdate('Y-m-d H:i:s'));
        $this->addToStorage(FieldConfig::ORDER_NUMBER, self::ORDER_NUMBER_VALUE);
        $this->addToStorage(FieldConfig::ORDER_TOTAL_INCL_TAX, self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfig::ORDER_TOTAL_EXCL_TAX, self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfig::ORDER_TAX_AMOUNT, self::PRODUCT_TAX_VALUE);
        $this->addPaymentToken(self::ORDER_NUMBER_VALUE);
    }

    private function insertFakeProduct()
    {
        $this->addToStorage(FieldConfig::PRODUCT_NAME . '0', self::PRODUCT_NAME_VALUE);
        $this->addToStorage(FieldConfig::PRODUCT_QUANTITY . '0', self::PRODUCT_QUANTITY_VALUE);
        $this->addToStorage(FieldConfig::PRODUCT_DISCOUNT . '0', self::PRODUCT_DISCOUNT_VALUE);
        $this->addToStorage(FieldConfig::PRODUCT_TAX . '0', self::PRODUCT_TAX_VALUE);
        $this->addToStorage(FieldConfig::PRODUCT_UNIT_PRICE . '0', self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfig::PRODUCT_PRICE_EXCL_TAX . '0', self::PRODUCT_PRICE_VALUE);
        $this->addToStorage(FieldConfig::PRODUCT_PRICE_INCL_TAX . '0', self::PRODUCT_PRICE_VALUE);
    }
}
