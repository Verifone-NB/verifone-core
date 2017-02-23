<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

namespace Verifone\Core\Tests\Unit\Storage;

use Verifone\Core\Storage\ArrayStorage;
use Verifone\Core\Exception\StorageValueOverwriteException;
use Verifone\Core\Exception\StorageKeyNotInKeyspaceException;

/**
 * Class ArrayStorageTest
 * @package Verifone\Core\Tests\Storage
 * @codeCoverageIgnore
 */
class ArrayStorageTest extends \PHPUnit_Framework_TestCase
{
    protected $arrayStorage;

    public function setUp()
    {
        $config = include 'fieldNamesConfigTestData.php';
        $this->arrayStorage = new ArrayStorage($config);
    }

    /**
     * @param $key
     *
     * @dataProvider providerValidKeys
     */
    public function testAddAndGetKeyAlreadyAddedThrowsException($key)
    {
        $originalValue = "valueTest";
        $anotherValue = "anotherValue";

        $this->arrayStorage->add($key, $originalValue);

        $this->expectException(StorageValueOverwriteException::class);
        $this->arrayStorage->add($key, $anotherValue);
    }

    /**
     * @param $key
     *
     * @dataProvider providerValidKeys
     */
    public function testAddAndGetKeyAlreadyAddedDoesntChangeOldValue($key)
    {
        $originalValue = "valueTest";
        $anotherValue = "anotherValue";

        $this->arrayStorage->add($key, $originalValue);

        try {
            $this->arrayStorage->add($key, $anotherValue);
        } catch (\Exception $e) {
        }

        $resultValue = $this->arrayStorage->get($key);
        $this->assertEquals($originalValue, $resultValue);
    }

    /**
     * @dataProvider providerCornerCases
     */
    public function testAddAndGetKeyNotInKeySpaceThrowsException($key)
    {
        $originalValue = "value1";

        $this->expectException(StorageKeyNotInKeyspaceException::class);
        $this->arrayStorage->add($key, $originalValue);
    }

    /**
     * @dataProvider providerCornerCases
     */
    public function testAddAndGetKeyNotInKeySpaceNotAdded($key)
    {
        $originalValue = "value1";

        try {
            $this->arrayStorage->add($key, $originalValue);
        } catch (\Exception $e) {

        }

        $resultValue = $this->arrayStorage->get($key);
        $this->assertFalse($resultValue);
    }

    /**
     * @param $key
     *
     * @dataProvider providerValidKeys
     */
    public function testGetValidUnaddedFromKeyspace($key)
    {
        $result = $this->arrayStorage->get($key);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider providerCornerCases
     */
    public function testGetUnaddedFromKeyspace($key)
    {
        $result = $this->arrayStorage->get($key);

        $this->assertFalse($result);
    }

    public function providerCornerCases()
    {
        return array(
            array('key1'), // key not in keyspace
            array(''), // empty key
            array(null), // null as key
            array(false), // false as key
            array(true), // true as key
            array('produc_test-0'), // not countable, but trying to be
            array('product_discount-'), // countable, but without number
            array('product_test2'), // incorrectly defined, no array mapped
            array('product_test6-asdf'), // string at the end instead of number
            array('a7'), // countable with no -
            array('a'), // Countable but acts like uncountable
            array('i-t-1-11_bi-unit-count-+1'), // Countable but acts like uncountable
        );
    }

    /**
     * @param $key1 original key 1
     * @param $key2 original key 2
     * @param $key3 original key 3
     * @throws StorageValueOverwriteException
     *
     * @dataProvider providerTestAddAndGet
     */
    public function testAddCountables($key1, $key2, $key3)
    {
        $originalValue = "valueTest";
        $originalValue2 = "valueTest2";
        $originalValue3 = "asdf";

        $this->arrayStorage->add($key1, $originalValue)
            ->add($key2, $originalValue2)
            ->add($key3, $originalValue3);

        $results = array_values($this->arrayStorage->getAsArray());
        $resultKeys = array_keys($this->arrayStorage->getAsArray());

        $this->assertContains($originalValue, $results);
        $this->assertContains($originalValue2, $results);
        $this->assertContains($originalValue3, $results);
        $this->assertContains($key1, $resultKeys);
        $this->assertContains($key2, $resultKeys);
        $this->assertContains($key3, $resultKeys);
    }

    public function providerTestAddAndGet()
    {
        return array(
            array(
                's-t-1-30_bi-name-0',
                's-t-1-30_bi-name-1',
                's-t-1-30_bi-name-2'
            ), // countables
            array(
                's-t-256-256_signature-two',
                's-f-1-30_software',
                's-f-1-10_software-version'
            ), // test non countables
            array(
                'l-t-1-20_bi-gross-amount-444',
                's-f-5-128_cancel-url',
                'i-t-1-11_bi-unit-count-0',
            ), // test both in the same
        );
    }

    public function providerValidKeys()
    {
        return array(
            array('s-f-1-36_merchant-agreement-code'),
            array('s-f-5-128_rejected-url'),
            array('s-t-256-256_signature-one'),
            array('s-t-1-30_bi-name-1'),
            array('i-t-1-11_bi-unit-count-4555'),
        );
    }
}
