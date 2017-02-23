<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\Configuration\Frontend;


use Verifone\Core\DependencyInjection\Configuration\Frontend\RedirectUrlsImpl;
use Verifone\Core\Exception\FieldValidationFailedException;

/**
 * Class RedirectUrlsImplTest
 * @package Verifone\Core\Tests\DependecyInjection\Configuration
 * @codeCoverageIgnore
 */
class RedirectUrlsImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $success
     * @param $rejected
     * @param $cancel
     * @param $expired
     * @param $error
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($success, $rejected, $cancel, $expired, $error)
    {
        $redirectUrls = new RedirectUrlsImpl($success, $rejected, $cancel, $expired, $error);
        $this->assertEquals($success, $redirectUrls->getSuccessUrl());
        $this->assertEquals($rejected, $redirectUrls->getRejectedUrl());
        $this->assertEquals($cancel, $redirectUrls->getCancelUrl());
        $this->assertEquals($expired, $redirectUrls->getExpiredUrl());
        $this->assertEquals($error, $redirectUrls->getErrorUrl());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('http://success/', 'http://rejected/', 'http://cancel/', 'http://expired/', 'http://error/'), //common
        );
    }
}
