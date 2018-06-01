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
        $route = new Binding(array('foo' => 'bar'));
    }

    public function testValidNameSetterAndTypeParameter()
    {
        $binding = new Binding(
            array('name' => 'test', 'setter' => 'getTest', 'type' => 'string')
        );
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals(
            'test',
            $binding->getName()
        );
        $this->assertEquals(
            'getTest',
            $binding->getSetter()
        );
        $this->assertEquals(
            'string',
            $binding->getType()
        );
    }

    public function testValidNameAndSetterParameter()
    {
        $binding = new Binding(array('name' => 'test', 'setter' => 'getTest'));
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals(
            'test',
            $binding->getName()
        );
        $this->assertEquals(
            'getTest',
            $binding->getSetter()
        );
        $this->assertNull($binding->getType());
    }

    public function testValidNameParameter()
    {
        $binding = new Binding(array('name' => 'test'));
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals(
            'test',
            $binding->getName()
        );
        $this->assertNull($binding->getSetter());
        $this->assertNull($binding->getType());
    }
}
