<?php

namespace SOW\BindingBundle\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Annotation\Binding;

class BindingTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidRouteParameter()
    {
        $route = new Binding(array('foo' => 'bar'));
    }

    public function testValidNameAndSetterParameter()
    {
        $binding = new Binding(array('name' => 'test', 'setter' => 'getTest'));
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getName());
        $this->assertEquals('getTest', $binding->getSetter());
    }

    public function testIncompliteValidNameParameter()
    {
        $binding = new Binding(array('name' => 'test'));
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getName());
        $this->assertNull($binding->getSetter());
    }

}