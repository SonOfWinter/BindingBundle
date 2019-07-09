<?php

/**
 * Binding test
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Tests\Annotation
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Annotation\Binding;

/**
 * Class BindingTest
 *
 * @package SOW\BindingBundle\Tests\Annotation
 */
class BindingTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidRouteParameter()
    {
        new Binding(['foo' => 'bar']);
    }

    public function testValidNameSetterAndTypeParameters()
    {
        $binding = new Binding(
            ['key' => 'test', 'setter' => 'getTest', 'type' => 'string']
        );
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertEquals('getTest', $binding->getSetter());
        $this->assertEquals('string', $binding->getType());
    }

    public function testValidNameAndSetterParameters()
    {
        $binding = new Binding(['key' => 'test', 'setter' => 'getTest']);
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertEquals('getTest', $binding->getSetter());
        $this->assertNull($binding->getType());
    }

    public function testValidNameParameter()
    {
        $binding = new Binding(['key' => 'test']);
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertNull($binding->getSetter());
        $this->assertNull($binding->getType());
    }

    public function testValidMinMaxAndNullableParameters()
    {
        $binding = new Binding(
            ['key' => 'test', 'nullable' => true, 'min' => 1, 'max' => 10]
        );
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertTrue($binding->isNullable());
        $this->assertEquals(1, $binding->getMin());
        $this->assertEquals(10, $binding->getMax());
    }

}
