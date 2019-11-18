<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Converter\Request;

use Verifone\Core\Converter\Request\HtmlConverter;
use Verifone\Core\Exception\UnableToConvertFieldsException;

/**
 * Class HtmlConverterTest
 * @package Verifone\Core\Tests\Converter\Request
 * @codeCoverageIgnore
 */
class HtmlConverterTest extends \AbstractConverterTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->converter = new HtmlConverter();
    }

    /**
     * @param $fields
     * @param $resultMiddle
     *
     * @dataProvider providerTestConvert
     */
    public function testConvert($fields, $resultMiddle, $action)
    {
        $expectedResult = '<form method="POST" id="verifone_form" action="' . $action . '" target="_parent">
Redirecting to VerifonePayment.
' . $resultMiddle . '<br>
<script type="text/javascript">document.getElementById("verifone_form").submit();</script>
</form>';

        $this->mockStorage->expects($this->exactly(2))
            ->method('getAsArray')
            ->will($this->returnValue($fields));

        $result = $this->converter->convert($this->mockStorage, $action);

        $this->assertEquals($expectedResult, $result);
    }

    public function providerTestConvert()
    {
        return array(
            array(array(), '', ''),
            array(array('field1' => 'value1'), '<input type="hidden" name="field1" value="value1" />' . "\n", 'jee'),
            array(
                array('field1' => 'value1', 'field2' => 'value2'),
                '<input type="hidden" name="field1" value="value1" />' . "\n" .
                '<input type="hidden" name="field2" value="value2" />' . "\n",
                'https://epayment.test.point.fi/pw/payment/'
            )
        );
    }
}
