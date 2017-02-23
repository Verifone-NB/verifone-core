<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service\Frontend;


use Verifone\Core\Tests\Unit\Service\AbstractServiceTest;

abstract class AbstractFrontendServiceTest extends AbstractServiceTest
{
    public function testConstructAndGetFields()
    {
        parent::testConstructAndGetFields();
        $this->mockConf->expects($this->once())
            ->method('getRedirectUrls')
            ->will($this->returnValue($this->mockUrls));
    }
    
    protected function checkKeys($keys)
    {
        parent::checkKeys($keys);
        $this->assertContains('s-f-5-128_cancel-url', $keys);
        $this->assertContains('s-f-5-128_error-url', $keys);
        $this->assertContains('s-f-5-128_expired-url', $keys);
        $this->assertContains('s-f-5-128_rejected-url', $keys);
        $this->assertContains('s-f-5-128_success-url', $keys);
        $this->assertContains('i-t-1-1_skip-confirmation-page', $keys);
    }
    
    protected function checkOrder($keys)
    {
        parent::checkOrder($keys);
        $this->assertContains('l-f-1-20_order-net-amount', $keys);
        $this->assertContains('l-f-1-20_order-vat-amount', $keys);
        $this->assertContains('i-t-1-1_save-payment-method', $keys);
    }
    
    protected function checkPaymentInfo($keys)
    {
        parent::checkPaymentInfo($keys);
        $this->assertContains('s-f-32-32_payment-token', $keys);
    }
}
