<?php
/**
 * AnnotationClassLoader test
 *
 * @package  SOW\BindingBundle\Tests\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Loader\AttributeClassLoader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Class AttributeClassLoaderTest
 *
 * @package SOW\BindingBundle\Tests\Loader
 */
class AttributeClassLoaderTest extends TestCase
{
    private AttributeClassLoader $loader;

    private string $bindingAttributeClass = 'SOW\\BindingBundle\\Attribute\\Binding';

    protected function setUp(): void
    {
        parent::setUp();
        $this->loader = $this->getClassLoader();
    }

    protected function setObjectAttribute($object, $attributeName, $value)
    {
        $reflection = new ReflectionObject($object);
        $property = $reflection->getProperty($attributeName);
        $property->setAccessible(true);
        $property->setValue(
            $object,
            $value
        );
    }

    public function getClassLoader()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        return $this->getMockBuilder(
            'SOW\BindingBundle\Loader\AttributeClassLoader'
        )->setConstructorArgs([$em, $this->bindingAttributeClass])
            ->getMockForAbstractClass();
    }

    # setBindingAttributeClass
    public function testChangeAttributeClass()
    {
        $newClass = 'SOW\\BindingBundle\\Tests\\Fixtures\\AttributeClasses\\TestAttributeObject';
        $this->loader->setBindingAttributeClass($newClass);
        $reflection = new ReflectionObject($this->loader);
        $property = $reflection->getProperty('bindingAttributeClass');
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
            'SOW\BindingBundle\Tests\Fixtures\AttributeClasses\AbstractClass'
        );
    }

    public function testLoadClass()
    {
        $collection = $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeObject'
        );
        $this->assertEquals(
            4,
            $collection->count()
        );
    }

    public function testLoadTypedClass()
    {
        $collection = $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeTypedMinMaxObject'
        );
        $this->assertEquals(
            4,
            $collection->count()
        );
    }

    public function testSupportsChecksTypeIfSpecified()
    {
        $this->assertTrue(
            $this->loader->supports(
                'class',
                'attribute'
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
