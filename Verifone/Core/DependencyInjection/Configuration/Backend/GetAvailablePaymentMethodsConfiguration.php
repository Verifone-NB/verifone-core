<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Configuration\Backend;


interface GetAvailablePaymentMethodsConfiguration extends BackendConfiguration
{
    public function __construct($privateKey, $merchantAgreementCode, $software, $softwareVersion, $urls, $currency);

    public function getCurrency();

}
