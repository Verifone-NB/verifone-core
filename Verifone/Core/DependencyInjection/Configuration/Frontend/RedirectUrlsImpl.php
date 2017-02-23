<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\DependencyInjection\Configuration\Frontend;

/**
 * A value object containing redirect urls
 * Class RedirectUrlsImpl
 * @package Verifone\Core\DependencyInjection\Configuration
 */
class RedirectUrlsImpl implements RedirectUrls
{
    private $success;
    private $rejected;
    private $cancel;
    private $expired;
    private $error;

    /**
     * RedirectUrlsImpl constructor.
     * sets redirect urls
     * @param $success string between 5 and 128 characters, can't be cut
     * @param $rejected string between 5 and 128 characters, can't be cut
     * @param $cancel string between 5 and 128 characters, can't be cut
     * @param $expired string between 5 and 128 characters, can't be cut
     * @param $error string between 5 and 128 characters, can't be cut
     */
    public function __construct($success, $rejected, $cancel, $expired, $error)
    {
        $this->success = $success;
        $this->rejected = $rejected;
        $this->cancel = $cancel;
        $this->expired = $expired;
        $this->error = $error;
    }

    public function getCancelUrl()
    {
        return $this->cancel;
    }

    public function getErrorUrl()
    {
        return $this->error;
    }

    public function getExpiredUrl()
    {
        return $this->expired;
    }

    public function getRejectedUrl()
    {
        return $this->rejected;
    }

    public function getSuccessUrl()
    {
        return $this->success;
    }
}
