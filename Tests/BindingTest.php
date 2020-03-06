<?php

/**
 * Binding test
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests;

use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class BindingTest
 *
 * @package SOW\BindingBundle\Tests
 */
class BindingTest extends TestCase
{
    public function testConstructWithValidParams()
    {
        $binding = new Binding(
            'foo',
            'setFoo',
            'string',
            0,
            100,
            null,
            'getFoo',
            true
        );
        $this->assertEquals('foo', $binding->getKey());
        $this->assertEquals('setFoo', $binding->getSetter());
        $this->assertEquals('getFoo', $binding->getGetter());
        $this->assertNull($binding->getSubCollection());
        $this->assertEquals('string', $binding->getType());
        $this->assertEquals('foo', $binding->__toString());
        $this->assertEquals(0, $binding->getMin());
        $this->assertEquals(100, $binding->getMax());
        $this->assertTrue($binding->isNullable());
    }

    public function testSetter()
    {
        $binding = new Binding(
            'foo',
            'setFoo'
        );
        $subCollection = new BindingCollection();
        $binding->setKey('bar');
        $binding->setSetter('setBar');
        $binding->setType('string');
        $binding->setMax(8);
        $binding->setMin(2);
        $binding->setGetter('getBar');
        $binding->setSubCollection($subCollection);
        $binding->setNullable(true);
        $this->assertEquals('bar', $binding->getKey());
        $this->assertEquals('setBar', $binding->getSetter());
        $this->assertEquals('getBar', $binding->getGetter());
        $this->assertEquals('string', $binding->getType());
        $this->assertEquals($subCollection, $binding->getSubCollection());
        $this->assertEquals(2, $binding->getMin());
        $this->assertEquals(8, $binding->getMax());
        $this->assertTrue($binding->isNullable());
    }

    public function testSerialize()
    {
        $binding = new Binding(
            'foo',
            'setFoo',
            'string',
            0,
            100,
            null,
            'getFoo',
            true
        );
        $serialize = $binding->serialize();

        $binding = new Binding(
            'bar',
            'setBar',
            'int'
        );
        $binding->unserialize($serialize);

        $this->assertEquals('foo', $binding->getKey());
        $this->assertEquals('setFoo', $binding->getSetter());
        $this->assertEquals('getFoo', $binding->getGetter());
        $this->assertEquals('string', $binding->getType());
        $this->assertEquals(0, $binding->getMin());
        $this->assertEquals(100, $binding->getMax());
        $this->assertTrue($binding->isNullable());
    }
}
