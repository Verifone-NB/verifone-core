<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service\Backend;


use Verifone\Core\Tests\Unit\Service\AbstractServiceTest;

abstract class AbstractBackendServiceTest extends AbstractServiceTest
{
    public function testConstructAndGetFields()
    {
        parent::testConstructAndGetFields();

        $this->mockProduct->expects($this->never())
            ->method('getName')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->never())
            ->method('getUnitPriceExclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->never())
            ->method('getPriceExclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->never())
            ->method('getPriceInclTax')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->never())
            ->method('getQuantity')
            ->will($this->returnValue(''));
        $this->mockProduct->expects($this->never())
            ->method('getDiscountPercentage')
            ->will($this->returnValue(''));
        
        $this->mockOrder->expects($this->never())
            ->method('getTimestamp')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getCurrency')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTotalInclTax')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTotalExclTax')
            ->will($this->returnValue(''));
        $this->mockOrder->expects($this->never())
            ->method('getTaxAmount')
            ->will($this->returnValue(''));

        $this->mockConf->expects($this->once())
            ->method('getUrls')
            ->will($this->returnValue(array('asdf')));
    }
    
    protected function checkKeys($keys) 
    {
        parent::checkKeys($keys);
        $this->assertContains('t-f-14-19_request-timestamp', $keys);
        $this->assertContains('s-f-1-30_operation', $keys);
        $this->assertContains('l-f-1-20_request-id', $keys);
    }
}
