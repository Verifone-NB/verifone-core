<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is released under commercial license by Lamia Oy.
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi)
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Service\FrontendResponse;


use Verifone\Core\Service\FrontendResponse\FrontendResponseServiceImpl;
use Verifone\Core\Tests\Unit\Service\TestStorage;

class FrontendResponseServiceTest extends \PHPUnit_Framework_TestCase
{
    private $mockStorage;
    private $mockOrder;
    
    public function setUp()
    {
        $this->mockStorage = new TestStorage();
        $this->mockOrder = $this->getMockBuilder('\Verifone\Core\DependencyInjection\Service\Interfaces\Order')
            ->getMock();
    }

    public function testInsertOrderNull()
    {
        $this->expectException(\TypeError::class);
        $service = new FrontendResponseServiceImpl($this->mockStorage, array());
        $service->insertOrder(null);
    }

    public function testAddingOrder()
    {
        $service = new FrontendResponseServiceImpl($this->mockStorage, array());
        $resultStorage = $service->getFields();

        $this->assertEquals($this->mockStorage, $resultStorage);

        $service->insertOrder($this->mockOrder);
        $fields = $resultStorage->getAsArray();
        $keys = array_keys($fields);
        $this->checkOrder($keys);
        $this->assertEquals(4, count($keys));
    }

    protected function checkOrder($keys)
    {
        $this->assertContains('s-f-1-36_order-number', $keys);
        $this->assertContains('t-f-14-19_order-timestamp', $keys);
        $this->assertContains('i-f-1-3_order-currency-code', $keys);
        $this->assertContains('l-f-1-20_order-gross-amount', $keys);
    }
}
