<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\DependecyInjection\CoreResponse;

use Verifone\Core\DependencyInjection\CoreResponse\CardImpl;

class CardImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $code
     * @param $id
     * @param $title
     * @param $validity
     *
     * @dataProvider providerTestConstructHappy
     */
    public function testConstructHappy($code, $id, $title, $validity)
    {
        $card = new CardImpl($code, $id, $title, $validity);
        $this->assertEquals($code, $card->getCode());
        $this->assertEquals($id, $card->getId());
        $this->assertEquals($title, $card->getTitle());
        $this->assertEquals($validity, $card->getValidity());
    }

    public function providerTestConstructHappy()
    {
        return array(
            array('d', 'c', 'aa', 'b'),
            array('', '', '', ''),
        );
    }
}
