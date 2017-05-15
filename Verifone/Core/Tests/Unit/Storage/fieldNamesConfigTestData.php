<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

/**
 * @codeCoverageIgnore
 */
return array(
    's-f-5-256_cancel-url' => array('type' => 'string', 'min' => 5, 'max' => 128),
    'i-f-1-3_currency-code' => array('type' => 'string', 'max' => 3, 'numeric' => true),
    's-f-5-256_error-url' => array('type' => 'string', 'min' => 5, 'max' => 128),
    's-f-5-256_expired-url' => array('type' => 'string', 'min' => 5, 'max' => 128),
    's-f-1-36_merchant-agreement-code' => array('type' => 'string', 'max' => 36),
    'locale-f-2-5_payment-locale' => array('type' => 'string', 'min' => 2, 'max' => 5),
    's-f-1-30_payment-method-code' => array('type' => 'string', 'max' => 30),
    's-f-5-256_rejected-url' => array('type' => 'string', 'min' => 5, 'max' => 128),
    's-t-256-256_signature-one' => array('type' => 'string', 'min' => 256, 'max' => 256),
    's-t-256-256_signature-two' => array('type' => 'string', 'min' => 256, 'max' => 256, 'optional' => true),
    'i-t-1-1_skip-confirmation-page' => array('type' => 'string', 'max' => 1, 'optional' => true, 'values' => array('0', '1')),
    's-f-1-30_software' => array('type' => 'string', 'max' => 30, 'cut' => true),
    's-f-1-10_software-version' => array('type' => 'string', 'max' => 10, 'cut' => true),
    's-f-5-256_success-url' => array('type' => 'string', 'min' => 5, 'max' => 128),
    'l-f-1-20_transaction-number' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    's-t-1-30_delivery-address-line-one' => array('type' => 'string', 'max' => 30, 'optional' => true, 'cut' => true),
    's-t-1-30_delivery-address-line-two' => array('type' => 'string', 'max' => 30, 'optional' => true, 'cut' => true),
    's-t-1-30_delivery-address-line-three' => array('type' => 'string', 'max' => 30, 'optional' => true, 'cut' => true),
    's-t-1-30_delivery-address-city' => array('type' => 'string', 'max' => 30, 'optional' => true, 'cut' => true),
    's-t-1-30_delivery-address-postal-code' => array('type' => 'string', 'max' => 30, 'optional' => true, 'cut' => true),
    'i-t-1-3_delivery-address-country-code' => array('type' => 'string', 'max' => 3, 'optional' => true, 'cut' => true),
    's-f-1-100_buyer-email-address' => array('type' => 'string', 'max' => 100, 'cut' => true),
    's-f-1-30_buyer-first-name' => array('type' => 'string', 'max' => 30, 'cut' => true),
    's-f-1-30_buyer-last-name' => array('type' => 'string', 'max' => 30, 'cut' => true),
    's-t-1-30_buyer-phone-number' => array('type' => 'string', 'max' => 30, 'cut' => true, 'optional' => true),
    'i-f-1-11_interface-version' => array('type' => 'string', 'max' => 1, 'values' => array(3)),
    's-f-1-30_operation' => array('type' => 'string', 'max' => 100),
    'i-f-1-3_order-currency-code' => array('type' => 'string', 'max' => 3, 'numeric' => true),
    's-f-1-36_order-number' => array('type' => 'string', 'max' => 36),
    'i-t-1-1_save-payment-method' => array('type' => 'string', 'values' => array('0', '1', '2', '3'), 'optional' => true),
    'l-f-1-20_order-vat-amount' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    't-f-14-19_order-timestamp' => array('type' => 'date', 'format' => 'Y-m-d H:i:s'),
    'l-f-1-20_order-gross-amount' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    'l-f-1-20_order-net-amount' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    't-f-14-19_payment-timestamp' => array('type' => 'date', 'format' => 'Y-m-d H:i:s'),
    's-f-32-32_payment-token' => array('type' => 'string', 'min' => 32, 'max' => 32),
    'i-t-1-4_bi-discount-percentage-' => array('type' => 'string', 'max' => 4, 'optional' => true, 'numeric' => true, 'cut' => true, 'countable' => true),
    's-t-1-30_bi-name-' => array('type' => 'string', 'max' => 30, 'cut' => true, 'optional' => true, 'countable' => true),
    'l-t-1-20_bi-gross-amount-' => array('type' => 'string', 'max' => 20, 'optional' => true, 'numeric' => true, 'cut' => true, 'countable' => true),
    'l-t-1-20_bi-net-amount-' => array('type' => 'string', 'max' => 20, 'optional' => true, 'numeric' => true, 'cut' => true, 'countable' => true),
    'i-t-1-11_bi-unit-count-' => array('type' => 'string', 'max' => 11, 'optional' => true, 'numeric' => true, 'cut' => true, 'countable' => true),
    'i-t-1-4_bi-vat-percentage-' => array('type' => 'string', 'max' => 4, 'optional' => true, 'numeric' => true, 'cut' => true, 'countable' => true),
    'l-t-1-20_bi-unit-cost-' => array('type' => 'string', 'max' => 20, 'optional' => true, 'numeric' => true, 'cut' => true, 'countable' => true),
    'i-t-1-3_recurring-payment-subscription-expected-period' => array('type' => 'string', 'optional' => true, 'cut' => true, 'max' => 3, 'numeric' => true),
    'i-t-1-1_recurring-payment' => array('type' => 'string', 'values' => array('0', '1'), 'optional' => true),
    's-t-1-30_recurring-payment-subscription-name' => array('type' => 'string', 'max' => 30, 'cut' => true, 'optional' => true),
    's-t-1-30_recurring-payment-subscription-code' => array('type' => 'string', 'max' => 30, 'cut' => true, 'optional' => true),
    'l-f-1-20_refund-amount' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    'i-f-1-3_refund-currency-code' => array('type' => 'string', 'max' => 3, 'numeric' => true),
    's-t-1-36_order-note' => array('type' => 'string', 'values' => array('Refund Request')),
    'l-f-1-20_request-id' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    't-f-14-19_request-timestamp' => array('type' => 'date', 'format' => 'Y-m-d H:i:s'),
    'l-t-1-20_saved-payment-method-id' => array('type' => 'string', 'max' => 20, 'numeric' => true),
    'produc_test-' => array('request_key' => 'asfda', 'countable' => false),
    'product_test2' => true,
    'product_test6-' => array('request_key' => 'asdf', 'countable' => true),
    'a' => array('request_key' => 'asdf', 'countable' => true),
);
