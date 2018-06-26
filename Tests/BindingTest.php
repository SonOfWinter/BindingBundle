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
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

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
            'string'
        );
        $this->assertEquals(
            'foo',
            $binding->getKey()
        );
        $this->assertEquals(
            'setFoo',
            $binding->getSetter()
        );
        $this->assertEquals(
            'string',
            $binding->getType()
        );
        $this->assertEquals(
            'foo',
            $binding->__toString()
        );
    }

    public function testSetter()
    {
        $binding = new Binding(
            'foo',
            'setFoo'
        );
        $binding->setKey('bar');
        $binding->setSetter('setBar');
        $binding->setType('string');
        $this->assertEquals(
            'bar',
            $binding->getKey()
        );
        $this->assertEquals(
            'setBar',
            $binding->getSetter()
        );
        $this->assertEquals(
            'string',
            $binding->getType()
        );
    }

    public function testSerialize()
    {
        $binding = new Binding(
            'foo',
            'setFoo',
            'string'
        );
        $serialize = $binding->serialize();

        $binding = new Binding(
            'bar',
            'setBar',
            'int'
        );
        $binding->unserialize($serialize);

        $this->assertEquals(
            'foo',
            $binding->getKey()
        );
        $this->assertEquals(
            'setFoo',
            $binding->getSetter()
        );
        $this->assertEquals(
            'string',
            $binding->getType()
        );
    }
}
