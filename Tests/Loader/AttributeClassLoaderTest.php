<?php
/**
 * AnnotationClassLoader test
 *
 * @package  SOW\BindingBundle\Tests\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests\Loader;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SOW\BindingBundle\Loader\AttributeClassLoader;

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

    protected function setObjectAttribute($object, $attributeName, $value): void
    {
        $reflection = new ReflectionObject($object);
        $property = $reflection->getProperty($attributeName);
        $property->setAccessible(true);
        $property->setValue(
            $object,
            $value
        );
    }

    public function getClassLoader(): MockObject
    {
        $em = $this->createMock(EntityManagerInterface::class);
        return $this->getMockBuilder(
            'SOW\BindingBundle\Loader\AttributeClassLoader'
        )->setConstructorArgs([$em, $this->bindingAttributeClass])
            ->getMockForAbstractClass();
    }

    # setBindingAttributeClass
    public function testChangeAttributeClass(): void
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
    public function testLoadWrongClass(): void
    {
        static::expectException('\InvalidArgumentException');
        $this->loader->load('WrongClass');
    }

    public function testLoadAbstractClass(): void
    {
        static::expectException('\InvalidArgumentException');
        $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AttributeClasses\AbstractClass'
        );
    }

    public function testLoadClass(): void
    {
        $collection = $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeObject'
        );
        $this->assertEquals(4, $collection->count());
    }

    public function testLoadTypedClass(): void
    {
        $collection = $this->loader->load(
            'SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeTypedMinMaxObject'
        );
        $this->assertEquals(4, $collection->count());
    }

    public function testSupportsChecksTypeIfSpecified(): void
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
}
