<?php
/**
 * Binder test
 *
 * @package  SOW\BindingBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Exception\BinderMaxValueException;
use SOW\BindingBundle\Exception\BinderMinValueException;
use SOW\BindingBundle\Loader\AttributeClassLoader;
use SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeNullableObject;
use SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeObject;
use SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeTypedMinMaxObject;

/**
 * Class BinderTest
 *
 * @package SOW\BindingBundle\Tests
 */
class BinderTest extends TestCase
{
    private $bindingAttributeClass = 'SOW\\BindingBundle\\Attribute\\Binding';

    private function getBinder(
        EntityManagerInterface $em,
        string $method,
        int $maxRecursiveCall = 10
    ): Binder {
        $attributeClassLoader = new AttributeClassLoader($em, $this->bindingAttributeClass);
        return new Binder($attributeClassLoader, $em, $maxRecursiveCall, $method);
    }

    public function testAttributeBinderWithAllProperties(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
            'age' => 25,
            'subObject' => [
                'lastname' => 'Bullock',
                'firstname' => 'Dale',
                'subSubObject' => [
                    'city' => 'Paris',
                    'country' => 'France',
                ],
            ],
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertEquals(
            $dataArray['subObject']['firstname'],
            $testObject->getSubObject()->getFirstname()
        );
        $this->assertEquals(
            $dataArray['subObject']['lastname'],
            $testObject->getSubObject()->getLastname()
        );
        $this->assertEquals(
            $dataArray['subObject']['subSubObject']['city'],
            $testObject->getSubObject()->getSubSubObject()->getCity()
        );
        $this->assertEquals(
            $dataArray['subObject']['subSubObject']['country'],
            $testObject->getSubObject()->getSubSubObject()->getCountry()
        );
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAttributeBinderWithAllPropertiesButMaxRecursiveReached(): void
    {
        static::expectException('SOW\BindingBundle\Exception\BinderRecursiveException');
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
            'age' => 25,
            'subObject' => [
                'lastname' => 'Bullock',
                'firstname' => 'Dale',
                'subSubObject' => [
                    'city' => 'Paris',
                    'country' => 'France',
                ],
            ],
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE, 0);
        $bindingService->bind($testObject, $dataArray);
    }

    public function testAttributeBinderWithOneProperty(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals('', $testObject->getFirstname());
        $this->assertEquals('', $testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAttributeGetCollectionWithoutResource(): void
    {
        static::expectException('SOW\BindingBundle\Exception\BinderConfigurationException');
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->getBindingCollection();
    }

    public function testAttributeGetCollectionWithResourceAndCollection(): void
    {
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->setResource(get_class($testObject));
        $collection = $bindingService->getBindingCollection();
        $this->assertEquals($collection, $bindingService->getBindingCollection());
    }

    public function testAttributeBinderWithExcludeProperties(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind(
            $testObject,
            $dataArray,
            [],
            ['firstname']
        );
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals('', $testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAttributeBinderWithWrongExcludeProperties(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind(
            $testObject,
            $dataArray,
            [],
            ['wrongValue']
        );
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAttributeBinderWithIncludeProperties(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind($testObject, $dataArray, ['lastname', 'firstname']);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAttributeBinderWithMissingIncludeProperties(): void
    {
        static::expectException('SOW\BindingBundle\Exception\BinderIncludeException');
        static::expectExceptionMessage('Missing mandatory keys : phone');
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind(
            $testObject,
            $dataArray,
            ['lastname', 'firstname', 'phone']
        );
    }

    public function testAttributeGetKeys(): void
    {
        $testObject = new TestAttributeObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $result = $bindingService->getKeys($testObject);
        $this->assertEquals(4, count($result));
        $this->assertTrue(in_array('lastname', $result));
        $this->assertTrue(in_array('firstname', $result));
        $this->assertTrue(in_array('userEmail', $result));
        $this->assertTrue(in_array('subObject', $result));
    }

    public function testAttributeBinderWithAllTypedAndMinMaxProperties(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 25,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['age'], $testObject->getAge());
        $this->assertEquals($dataArray['letterList'], $testObject->getLetterList());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAttributeBinderWithMaxIntPropertyError(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 125,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(100, $e->getMax());
            $this->assertEquals('age', $e->getKey());
            $this->assertEquals('age must have a value less than : 100', $e->getMessage());
        }
    }

    public function testAttributeBinderWithMinIntPropertyError(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => -25,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(0, $e->getMin());
            $this->assertEquals('age', $e->getKey());
            $this->assertEquals('age must have a value more than : 0', $e->getMessage());
        }
    }

    public function testAttributeBinderWithMaxStringPropertyError(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryanapoiutonugindpoad',
            'age' => 20,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(20, $e->getMax());
            $this->assertEquals('firstname', $e->getKey());
            $this->assertEquals('firstname must have a value less than : 20', $e->getMessage());
        }
    }

    public function testAttributeBinderWithMinStringPropertyError(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'a',
            'age' => 20,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(2, $e->getMin());
            $this->assertEquals('firstname', $e->getKey());
            $this->assertEquals('firstname must have a value more than : 2', $e->getMessage());
        }
    }

    public function testAttributeBinderWithMaxArrayPropertyError(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 20,
            'letterList' => ['a', 'b', 'c', 'd', 'e'],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(3, $e->getMax());
            $this->assertEquals('letterList', $e->getKey());
            $this->assertEquals('letterList must have a value less than : 3', $e->getMessage());
        }
    }

    public function testAttributeBinderWithMinArrayPropertyError(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 20,
            'letterList' => [],
        ];
        $testObject = new TestAttributeTypedMinMaxObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(1, $e->getMin());
            $this->assertEquals('letterList', $e->getKey());
            $this->assertEquals('letterList must have a value more than : 1', $e->getMessage());
        }
    }

    public function testAttributeBinderWithAllNullProperties(): void
    {
        static::expectException('SOW\BindingBundle\Exception\BinderNullableException');
        static::expectExceptionMessage('Key lastname cannot be null');
        $dataArray = [
            'lastname' => null,
            'firstname' => null,
        ];
        $testObject = new TestAttributeNullableObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind($testObject, $dataArray);
    }

    public function testAttributeBinderWithSomeNullProperties(): void
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => null,
        ];
        $testObject = new TestAttributeNullableObject();
        $em = $this->createMock(EntityManagerInterface::class);
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertNull($dataArray['firstname']);
    }
}
