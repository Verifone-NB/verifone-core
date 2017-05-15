<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Szymon Nosal <simon@lamia.fi>
 * @author     Irina Mäkipaja <irina@lamia.fi>
 *
 */

namespace Verifone\Core\Configuration;

/**
 * Class FieldConfig
 * @package Verifone\Core\Configuration
 * Contains the field configurations for verifone fields.
 *
 * The purpose of this class is to contain the verifone field names so that they won't have to be copied all over the
 * project and the configurations for validating and manipulating those fields.
 * If the option is empty, then a default value is used in validation / manipulation.
 * Default values for fields:
 * min: 1, max: 10000, cut: false, optional: false, numeric: false, values: null, countable: false
 */
class FieldConfigImpl implements FieldConfig
{
    const CONFIG_CANCEL_URL = 's-f-5-256_cancel-url';
    const CONFIG_CURRENCY = 'i-f-1-3_currency-code';
    const CONFIG_ERROR_URL = 's-f-5-256_error-url';
    const CONFIG_EXPIRED_URL = 's-f-5-256_expired-url';
    const CONFIG_MERCHANT_AGREEMENT_CODE = 's-f-1-36_merchant-agreement-code';
    const CONFIG_REJECTED_URL = 's-f-5-256_rejected-url';
    const CONFIG_SKIP_CONFIRMATION = 'i-t-1-1_skip-confirmation-page';
    const CONFIG_SOFTWARE = 's-f-1-30_software';
    const CONFIG_SOFTWARE_VERSION = 's-f-1-10_software-version';
    const CONFIG_SUCCESS_URL = 's-f-5-256_success-url';
    const CONFIG_TRANSACTION = 'l-f-1-20_transaction-number';
    const CUSTOMER_ADDRESS_LINE_1 = 's-t-1-30_delivery-address-line-one';
    const CUSTOMER_ADDRESS_LINE_2 = 's-t-1-30_delivery-address-line-two';
    const CUSTOMER_ADDRESS_LINE_3 = 's-t-1-30_delivery-address-line-three';
    const CUSTOMER_ADDRESS_CITY = 's-t-1-30_delivery-address-city';
    const CUSTOMER_ADDRESS_POSTAL = 's-t-1-30_delivery-address-postal-code';
    const CUSTOMER_ADDRESS_COUNTRY = 'i-t-1-3_delivery-address-country-code';
    const CUSTOMER_EMAIL = 's-f-1-100_buyer-email-address';
    const CUSTOMER_EXTERNAL_ID = 's-t-1-255_buyer-external-id';
    const CUSTOMER_FIRST_NAME = 's-f-1-30_buyer-first-name';
    const CUSTOMER_LAST_NAME = 's-f-1-30_buyer-last-name';
    const CUSTOMER_PHONE_NUMBER = 's-t-1-30_buyer-phone-number';
    const INTERFACE_VERSION ='i-f-1-11_interface-version';
    const OPERATION = 's-f-1-30_operation';
    const ORDER_CURRENCY = 'i-f-1-3_order-currency-code';
    const ORDER_NUMBER = 's-f-1-36_order-number';
    const ORDER_TAX_AMOUNT = 'l-f-1-20_order-vat-amount';
    const ORDER_TIMESTAMP = 't-f-14-19_order-timestamp';
    const ORDER_TOTAL_INCL_TAX = 'l-f-1-20_order-gross-amount';
    const ORDER_TOTAL_EXCL_TAX = 'l-f-1-20_order-net-amount';
    const PAYMENT_LOCALE = 'locale-f-2-5_payment-locale';
    const PAYMENT_METHOD = 's-f-1-30_payment-method-code';
    const FRONTEND_PAYMENT_METHOD = 's-t-1-30_payment-method-code';
    const PAYMENT_DYNAMIC_FEEDBACK = 's-t-1-1024_dynamic-feedback';
    const PAYMENT_PAN_FIRST_6 = 'i-t-6-6_card-pan-first6';
    const PAYMENT_PAN_LAST_2 = 'i-t-2-2_card-pan-last2';
    const PAYMENT_SAVE_METHOD = 'i-t-1-1_save-payment-method';
    const PAYMENT_SAVED_METHOD_ID = 'l-t-1-20_saved-payment-method-id';
    const PAYMENT_TIMESTAMP = 't-f-14-19_payment-timestamp';
    const PAYMENT_TOKEN = 's-f-32-32_payment-token';
    const PRODUCT_DISCOUNT = 'i-t-1-4_bi-discount-percentage-';
    const PRODUCT_NAME = 's-t-1-30_bi-name-';
    const PRODUCT_PRICE_INCL_TAX = 'l-t-1-20_bi-gross-amount-';
    const PRODUCT_PRICE_EXCL_TAX = 'l-t-1-20_bi-net-amount-';
    const PRODUCT_QUANTITY = 'i-t-1-11_bi-unit-count-';
    const PRODUCT_TAX = 'i-t-1-4_bi-vat-percentage-';
    const PRODUCT_UNIT_PRICE = 'l-t-1-20_bi-unit-cost-';
    const RECURRING_PERIOD = 'i-t-1-3_recurring-payment-subscription-expected-period';
    const RECURRING_PAYMENT = 'i-t-1-1_recurring-payment';
    const RECURRING_SUBSCRIPTION_NAME = 's-t-1-30_recurring-payment-subscription-name';
    const RECURRING_SUBSCRIPTION_CODE = 's-t-1-30_recurring-payment-subscription-code';
    const REFUND_AMOUNT = 'l-f-1-20_refund-amount';
    const REFUND_CURRENCY = 'i-f-1-3_refund-currency-code';
    const ORDER_NOTE = 's-t-1-36_order-note';
    const REQUEST_ID = 'l-f-1-20_request-id';
    const REQUEST_TIMESTAMP = 't-f-14-19_request-timestamp';
    const RESPONSE_CANCEL_REASON = 's-t-1-30_cancel-reason';
    const RESPONSE_ERROR_MESSAGE = 's-f-1-30_error-message';
    const RESPONSE_ID = 'l-f-1-20_response-id';
    const RESPONSE_PAYMENT_METHOD_MIN = 'l-t-1-20_payment-method-min-';
    const RESPONSE_PAYMENT_METHOD_MAX = 'l-t-1-20_payment-method-max-';
    const RESPONSE_PAYMENT_METHOD_CODE = 's-t-1-30_payment-method-code-';
    const RESPONSE_PAYMENT_METHOD_TYPE = 's-t-1-30_payment-method-type-';
    const SIGNATURE_ONE = 's-t-256-256_signature-one';
    const SIGNATURE_TWO = 's-t-256-256_signature-two';
    
    /**
     * Get general verifone field configuration with field names mapped to their corresponding option values.
     * @return array with structure verifoneFieldName => options array. Options array is of structure optionName => value
     */
    public function getConfig()
    {
        return array(
            self::CONFIG_CANCEL_URL => array(
                'type' => 'string',
                'min' => 5,
                'max' => 128
            ),
            self::CONFIG_CURRENCY => array(
                'type' => 'string',
                'max' => 3,
                'numeric' => true
            ),
            self::CONFIG_ERROR_URL => array(
                'type' => 'string',
                'min' => 5,
                'max' => 128
            ),
            self::CONFIG_EXPIRED_URL => array(
                'type' => 'string',
                'min' => 5,
                'max' => 128
            ),
            self::CONFIG_MERCHANT_AGREEMENT_CODE => array(
                'type' => 'string',
                'max' => 36
            ),
            self::PAYMENT_DYNAMIC_FEEDBACK => array(
                'type' => 'string',
                'optional' => true
            ),
            self::PAYMENT_LOCALE => array(
                'type' => 'string',
                'min' => 2,
                'max' => 5
            ),
            self::PAYMENT_METHOD => array(
                'type' => 'string',
                'max' => 30
            ),
            self::FRONTEND_PAYMENT_METHOD => array(
                'optional' => true,
                'type' => 'string',
                'max' => 30
            ),
            self::CONFIG_REJECTED_URL => array(
                'type' => 'string',
                'min' => 5,
                'max' => 128
            ),
            self::SIGNATURE_ONE => array(
                'type' => 'string',
                'min' => 256,
                'max' => 256
            ),
            self::SIGNATURE_TWO => array(
                'type' => 'string',
                'min' => 256,
                'max' => 256,
                'optional' => true
            ),
            self::CONFIG_SKIP_CONFIRMATION => array(
                'type' => 'string',
                'max' => 1,
                'optional' => true,
                'values' => array('0', '1')
            ),
            self::CONFIG_SOFTWARE => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true
            ),
            self::CONFIG_SOFTWARE_VERSION => array(
                'type' => 'string',
                'max' => 10,
                'cut' => true
            ),
            self::CONFIG_SUCCESS_URL => array(
                'type' => 'string',
                'min' => 5,
                'max' => 128
            ),
            self::CONFIG_TRANSACTION => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true
            ),
            self::CUSTOMER_ADDRESS_LINE_1 => array(
                'type' => 'string',
                'max' => 30,
                'optional' => true,
                'cut' => true
            ),
            self::CUSTOMER_ADDRESS_LINE_2 => array(
                'type' => 'string',
                'max' => 30,
                'optional' => true,
                'cut' => true
            ),
            self::CUSTOMER_ADDRESS_LINE_3 => array(
                'type' => 'string',
                'max' => 30,
                'optional' => true,
                'cut' => true
            ),
            self::CUSTOMER_ADDRESS_CITY => array(
                'type' => 'string',
                'max' => 30,
                'optional' => true,
                'cut' => true
            ),
            self::CUSTOMER_ADDRESS_POSTAL => array(
                'type' => 'string',
                'max' => 30,
                'optional' => true,
                'cut' => true
            ),
            self::CUSTOMER_ADDRESS_COUNTRY => array(
                'type' => 'string',
                'max' => 3,
                'optional' => true,
                'cut' => true
            ),
            self::CUSTOMER_EMAIL => array(
                'type' => 'string',
                'max' => 100,
                'cut' => true
            ),
            self::CUSTOMER_EXTERNAL_ID => array(
                'type' => 'string',
                'max' => 255,
                'cut' => true,
                'optional' => true
            ),
            self::CUSTOMER_FIRST_NAME => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true
            ),
            self::CUSTOMER_LAST_NAME => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true
            ),
            self::CUSTOMER_PHONE_NUMBER => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true,
                'optional' => true
            ),
            self::INTERFACE_VERSION => array(
                'type' => 'string',
                'max' => 1,
                'values' => array(3, 4, 5)
            ),
            self::OPERATION => array(
                'type' => 'string',
                'max' => 100
            ),
            self::ORDER_CURRENCY => array(
                'type' => 'string',
                'max' => 3,
                'numeric' => true
            ),
            self::ORDER_NUMBER => array(
                'type' => 'string',
                'max' => 36
            ),
            self::PAYMENT_SAVE_METHOD => array(
                'type' => 'string',
                'values' => array('0', '1', '2', '3'),
                'optional' => true
            ),
            self::ORDER_TAX_AMOUNT => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true
            ),
            self::ORDER_TIMESTAMP => array(
                'type' => 'date',
                'format' => 'Y-m-d H:i:s'
            ),
            self::ORDER_TOTAL_INCL_TAX => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true
            ),
            self::ORDER_TOTAL_EXCL_TAX => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true
            ),
            self::PAYMENT_TIMESTAMP => array(
                'type' => 'date',
                'format' => 'Y-m-d H:i:s'
            ),
            self::PAYMENT_TOKEN => array(
                'type' => 'string',
                'min' => 32,
                'max' => 32
            ),
            self::PRODUCT_DISCOUNT => array(
                'type' => 'string',
                'max' => 4,
                'optional' => true,
                'numeric' => true,
                'cut' => true,
                'countable' => true
            ),
            self::PRODUCT_NAME => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true,
                'optional' => true,
                'countable' => true
            ),
            self::PRODUCT_PRICE_INCL_TAX => array(
                'type' => 'string',
                'max' => 20,
                'optional' => true,
                'numeric' => true,
                'cut' => true,
                'countable' => true
            ),
            self::PRODUCT_PRICE_EXCL_TAX => array(
                'type' => 'string',
                'max' => 20,
                'optional' => true,
                'numeric' => true,
                'cut' => true,
                'countable' => true
            ),
            self::PRODUCT_QUANTITY => array(
                'type' => 'string',
                'max' => 11,
                'optional' => true,
                'numeric' => true,
                'cut' => true,
                'countable' => true
            ),
            self::PRODUCT_TAX => array(
                'type' => 'string',
                'max' => 4,
                'optional' => true,
                'numeric' => true,
                'cut' => true,
                'countable' => true
            ),
            self::PRODUCT_UNIT_PRICE => array(
                'type' => 'string',
                'max' => 20,
                'optional' => true,
                'numeric' => true,
                'cut' => true,
                'countable' => true
            ),
            self::RECURRING_PERIOD => array(
                'type' => 'string',
                'optional' => true,
                'cut' => true,
                'max' => 3,
                'numeric' => true
            ),
            self::RECURRING_PAYMENT => array(
                'type' => 'string',
                'values' => array('0', '1'),
                'optional' => true
            ),
            self::RECURRING_SUBSCRIPTION_NAME => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true,
                'optional' => true
            ),
            self::RECURRING_SUBSCRIPTION_CODE => array(
                'type' => 'string',
                'max' => 30,
                'cut' => true,
                'optional' => true
            ),
            self::REFUND_AMOUNT => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true
            ),
            self::REFUND_CURRENCY => array(
                'type' => 'string',
                'max' => 3,
                'numeric' => true
            ),
            self::ORDER_NOTE => array(
                'type' => 'string',
                'optional' => true,
                'max' => 36,
                'cut' => true
            ),
            self::REQUEST_ID => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true
            ),
            self::REQUEST_TIMESTAMP => array(
                'type' => 'date',
                'format' => 'Y-m-d H:i:s'
            ),
            self::PAYMENT_SAVED_METHOD_ID => array(
                'type' => 'string',
                'max' => 20,
                'numeric' => true,
                'optional' => true
            ),
        );

    }
}
