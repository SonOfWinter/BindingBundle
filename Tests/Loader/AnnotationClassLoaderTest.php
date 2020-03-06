<?php

/**
 * AnnotationClassLoader test
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Tests\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Class AnnotationClassLoaderTest
 *
 * @package SOW\BindingBundle\Tests\Loader
 */
class AnnotationClassLoaderTest extends TestCase
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var AnnotationClassLoader
     */
    private $loader;

    private $bindingAnnotationClass = 'SOW\\BindingBundle\\Annotation\\Binding';

    protected function setUp(): void
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
        $property->setValue(
            $object,
            $value
        );
    }

    public function getReader()
    {
        return $this->getMockBuilder('Doctrine\Common\Annotations\Reader')
            ->disableOriginalConstructor()->getMock();
    }

    public function getClassLoader($reader)
    {
        $em = $this->createMock(EntityManagerInterface::class);
        return $this->getMockBuilder(
            'SOW\BindingBundle\Loader\AnnotationClassLoader'
        )->setConstructorArgs([$reader, $em, $this->bindingAnnotationClass])
            ->getMockForAbstractClass();
    }

    # setBindingAnnotationClass

    public function testChangeAnnotationClass()
    {
        $newClass
            = 'SOW\\BindingBundle\\Tests\\Fixtures\\AnnotatedClasses\\TestObject';
        $this->loader->setBindingAnnotationClass($newClass);
        $reflection = new \ReflectionObject($this->loader);
        $property = $reflection->getProperty('bindingAnnotationClass');
        $property->setAccessible(true);
        $this->assertEquals(
            $newClass,
            $property->getValue($this->loader)
        );
    }

    # load

    public function testLoadWrongClass()
    {
        static::expectException('\InvalidArgumentException');
        $this->loader->load('WrongClass');
    }

    public function testLoadAbstractClass()
    {
        static::expectException('\InvalidArgumentException');
        $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\AbstractClass'
        );
    }

    public function testLoadClass()
    {
        $collection = $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject'
        );
        $this->assertEquals(
            4,
            $collection->count()
        );
    }

    public function testLoadTypedClass()
    {
        $collection = $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedObject'
        );
        $this->assertEquals(
            3,
            $collection->count()
        );
    }

    public function testSupportsChecksTypeIfSpecified()
    {
        $this->assertTrue(
            $this->loader->supports(
                'class',
                'annotation'
            )
        );
        $this->assertFalse(
            $this->loader->supports(
                'class',
                'foo'
            )
        );
    }

    public function testGetResolverDoesNothing()
    {
        $this->assertTrue(empty($this->loader->getResolver()));
    }

    public function testSetResolverDoesNothing()
    {
        $lri = $this->createMock(LoaderResolverInterface::class);
        $this->assertTrue(empty($this->loader->setResolver($lri)));
    }
}
