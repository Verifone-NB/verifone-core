<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Service\Backend;

use Verifone\Core\Converter\Response\ResponseConverter;
use Verifone\Core\Service\Service;

/**
 * Interface BackendService
 * @package Verifone\Core\Service\Backend
 * contains everything that is in common across all the backend services
 */
interface BackendService extends Service
{
    /**
     * @return array of urls the request is tried to send to
     */
    public function getUrls();

    /**
     * @return ResponseConverter for converting the verifone response to general response
     */
    public function getResponseConverter();

    /**
     * @return array of fields that should match in both the request and the response.
     */
    public function getMatchingFields();
}
