<?php

namespace SOW\BindingBundle\Tests;

use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\Resource\ResourceInterface;

class BindingTest extends TestCase
{
    public function testConstructWithValidParams()
    {
        $binding = new Binding('foo', 'setFoo');
        $this->assertEquals('foo', $binding->getKey());
        $this->assertEquals('setFoo', $binding->getSetter());
        $this->assertEquals('foo', $binding->__toString());
    }

    public function testSetter()
    {
        $binding = new Binding('foo', 'setFoo');
        $binding->setKey('bar');
        $binding->setSetter('setBar');
        $this->assertEquals('bar', $binding->getKey());
        $this->assertEquals('setBar', $binding->getSetter());
    }

    public function testSerialize()
    {
        $binding = new Binding('foo', 'setFoo');
        $serialize = $binding->serialize();

        $binding = new Binding('bar', 'setBar');
        $binding->unserialize($serialize);

        $this->assertEquals('foo', $binding->getKey());
        $this->assertEquals('setFoo', $binding->getSetter());
    }
}