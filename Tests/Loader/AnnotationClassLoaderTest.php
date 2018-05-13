<?php

namespace SOW\BindingBundle\Tests\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Loader\AnnotationClassLoader;

class AnnotationClassLoaderTest extends TestCase
{
    /** @var AnnotationReader */
    private $reader;

    /** @var AnnotationClassLoader */
    private $loader;

    protected function setUp()
    {
        parent::setUp();
        $this->reader = new AnnotationReader();
        $this->loader = $this->getClassLoader($this->reader);
    }

    protected function setObjectAttribute($object, $attributeName, $value)
    {
        $reflection = new \ReflectionObject($object);
        $property = $reflection->getProperty($attributeName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    public function getReader()
    {
        return $this->getMockBuilder('Doctrine\Common\Annotations\Reader')->disableOriginalConstructor()->getMock();
    }

    public function getClassLoader($reader)
    {
        return $this->getMockBuilder('SOW\BindingBundle\Loader\AnnotationClassLoader')->setConstructorArgs([$reader])
                    ->getMockForAbstractClass();
    }

    # setBindingAnnotationClass

    public function testChangeAnnotationClass()
    {
        $newClass = 'SOW\\BindingBundle\\Tests\\Fixtures\\AnnotatedClasses\\TestObject';
        $this->loader->setBindingAnnotationClass($newClass);
        $reflection = new \ReflectionObject($this->loader);
        $property = $reflection->getProperty('bindingAnnotationClass');
        $property->setAccessible(true);
        $this->assertEquals($newClass, $property->getValue($this->loader));
    }

    # load

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadWrongClass()
    {
        $this->loader->load('WrongClass');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadAbstractClass()
    {
        $this->loader->load('SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\AbstractClass');
    }

    public function testLoadClass()
    {
        $collection = $this->loader->load('SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject');
        $this->assertEquals(2, $collection->count());
    }

    public function testLoadTypedClass()
    {
        $collection = $this->loader->load('SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedObject');
        $this->assertEquals(3, $collection->count());
    }

    public function testSupportsChecksTypeIfSpecified()
    {
        $this->assertTrue($this->loader->supports('class', 'annotation'));
        $this->assertFalse($this->loader->supports('class', 'foo'));
    }
}