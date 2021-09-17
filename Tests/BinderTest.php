<?php
/**
 * Binder test
 *
 * @package  SOW\BindingBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Exception\BinderMaxValueException;
use SOW\BindingBundle\Exception\BinderMinValueException;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Loader\AttributeClassLoader;
use SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses\ProxyTestObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestNullableObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedMinMaxObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedObject;
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
    private $bindingAnnotationClass = 'SOW\\BindingBundle\\Annotation\\Binding';
    private $bindingAttributeClass = 'SOW\\BindingBundle\\Attribute\\Binding';

    private function getBinder(EntityManagerInterface $em, string $method, int $maxRecursiveCall = 10): Binder
    {
        $reader = new AnnotationReader();
        $annotationClassLoader = new AnnotationClassLoader($reader, $em, $this->bindingAnnotationClass);
        $attributeClassLoader = new AttributeClassLoader($em, $this->bindingAttributeClass);
        return new Binder($annotationClassLoader, $attributeClassLoader, $em, $maxRecursiveCall, $method);
    }
    
    public function testAnnotationBinderWithAllProperties()
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
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertEquals($dataArray['subObject']['firstname'], $testObject->getSubObject()->getFirstname());
        $this->assertEquals($dataArray['subObject']['lastname'], $testObject->getSubObject()->getLastname());
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

    public function testAnnotationBinderWithAllPropertiesButMaxRecursiveReached()
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
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION, 0);
        $bindingService->bind($testObject, $dataArray);
    }

    public function testAnnotationBinderWithOneProperty()
    {
        $dataArray = [
            'lastname' => 'Bullock',
        ];
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertNull($testObject->getFirstname());
        $this->assertNull($testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAnnotationGetCollectionWithoutResource()
    {
        static::expectException('SOW\BindingBundle\Exception\BinderConfigurationException');
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->getBindingCollection();
    }

    public function testAnnotationGetCollectionWithResourceAndCollection()
    {
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->setResource(get_class($testObject));
        $collection = $bindingService->getBindingCollection();
        $this->assertEquals($collection, $bindingService->getBindingCollection());
    }

    public function testAnnotationBinderWithAllTypedProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 25,
        ];
        $testObject = new TestTypedObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['age'], $testObject->getAge());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAnnotationBinderWithWrongTypedProperties()
    {
        static::expectException('SOW\BindingBundle\Exception\BinderTypeException');
        static::expectExceptionMessage('Wrong firstname parameter type. Expected : string, received : double');
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 5.7,
            'age' => true,
        ];
        $testObject = new TestTypedObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
    }

    public function testAnnotationBinderWithProxyResource()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 5.7,
            'age' => true,
            'subObject' => [
                'lastname' => 'Bullock',
            ],
        ];
        $testObject = new ProxyTestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        $metadata = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $metadata->rootEntityName = TestObject::class;
        $em->expects($this->exactly(2))->method("getClassMetadata")->will($this->returnValue($metadata));
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray["subObject"]['lastname'], $testObject->getSubObject()->getLastname());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAnnotationBinderWithExcludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind(
            $testObject,
            $dataArray,
            [],
            ['firstname']
        );
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertNull($testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAnnotationBinderWithWrongExcludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
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

    public function testAnnotationBinderWithIncludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray, ['lastname', 'firstname']);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['userEmail'], $testObject->getUserEmail());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAnnotationBinderWithMissingIncludeProperties()
    {
        static::expectException('SOW\BindingBundle\Exception\BinderIncludeException');
        static::expectExceptionMessage('Missing mandatory keys : phone');
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
        ];
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind(
            $testObject,
            $dataArray,
            ['lastname', 'firstname', 'phone']
        );
    }

    public function testAnnotationGetKeys()
    {
        $testObject = new TestObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $result = $bindingService->getKeys($testObject);
        $this->assertEquals(4, count($result));
        $this->assertTrue(in_array('lastname', $result));
        $this->assertTrue(in_array('firstname', $result));
        $this->assertTrue(in_array('userEmail', $result));
        $this->assertTrue(in_array('subObject', $result));
    }

    public function testAnnotationBinderWithAllTypedAndMinMaxProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 25,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['age'], $testObject->getAge());
        $this->assertEquals($dataArray['letterList'], $testObject->getLetterList());
        $this->assertNull($testObject->getNotBindProperty());
    }

    public function testAnnotationBinderWithMaxIntPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 125,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(100, $e->getMax());
            $this->assertEquals('age', $e->getKey());
            $this->assertEquals('age must have a value less than : 100', $e->getMessage());
        }
    }

    public function testAnnotationBinderWithMinIntPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => -25,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(0, $e->getMin());
            $this->assertEquals('age', $e->getKey());
            $this->assertEquals('age must have a value more than : 0', $e->getMessage());
        }
    }

    public function testAnnotationBinderWithMaxStringPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryanapoiutonugindpoad',
            'age' => 20,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(20, $e->getMax());
            $this->assertEquals('firstname', $e->getKey());
            $this->assertEquals('firstname must have a value less than : 20', $e->getMessage());
        }
    }

    public function testAnnotationBinderWithMinStringPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'a',
            'age' => 20,
            'letterList' => ['a', 'b', 'c'],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(2, $e->getMin());
            $this->assertEquals('firstname', $e->getKey());
            $this->assertEquals('firstname must have a value more than : 2', $e->getMessage());
        }
    }

    public function testAnnotationBinderWithMaxArrayPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 20,
            'letterList' => ['a', 'b', 'c', 'd', 'e'],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(3, $e->getMax());
            $this->assertEquals('letterList', $e->getKey());
            $this->assertEquals('letterList must have a value less than : 3', $e->getMessage());
        }
    }

    public function testAnnotationBinderWithMinArrayPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 20,
            'letterList' => [],
        ];
        $testObject = new TestTypedMinMaxObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        try {
            $bindingService->bind($testObject, $dataArray);
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(1, $e->getMin());
            $this->assertEquals('letterList', $e->getKey());
            $this->assertEquals('letterList must have a value more than : 1', $e->getMessage());
        }
    }

    public function testAnnotationBinderWithAllNullProperties()
    {
        static::expectException('SOW\BindingBundle\Exception\BinderNullableException');
        static::expectExceptionMessage('Key lastname cannot be null');
        $dataArray = [
            'lastname' => null,
            'firstname' => null,
        ];
        $testObject = new TestNullableObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
    }

    public function testAnnotationBinderWithSomeNullProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => null,
        ];
        $testObject = new TestNullableObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ANNOTATION);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertNull($dataArray['firstname']);
    }
    
    public function testAttributeBinderWithAllProperties()
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
        $this->assertEquals($dataArray['subObject']['firstname'], $testObject->getSubObject()->getFirstname());
        $this->assertEquals($dataArray['subObject']['lastname'], $testObject->getSubObject()->getLastname());
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

    public function testAttributeBinderWithAllPropertiesButMaxRecursiveReached()
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

    public function testAttributeBinderWithOneProperty()
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

    public function testAttributeGetCollectionWithoutResource()
    {
        static::expectException('SOW\BindingBundle\Exception\BinderConfigurationException');
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->getBindingCollection();
    }

    public function testAttributeGetCollectionWithResourceAndCollection()
    {
        $testObject = new TestAttributeObject();
        
        $em = $this->createMock(EntityManagerInterface::class);
        
        $bindingService = $this->getBinder($em, Binder::METHOD_ATTRIBUTE);
        $bindingService->setResource(get_class($testObject));
        $collection = $bindingService->getBindingCollection();
        $this->assertEquals($collection, $bindingService->getBindingCollection());
    }

    public function testAttributeBinderWithExcludeProperties()
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

    public function testAttributeBinderWithWrongExcludeProperties()
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

    public function testAttributeBinderWithIncludeProperties()
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

    public function testAttributeBinderWithMissingIncludeProperties()
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

    public function testAttributeGetKeys()
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

    public function testAttributeBinderWithAllTypedAndMinMaxProperties()
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

    public function testAttributeBinderWithMaxIntPropertyError()
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

    public function testAttributeBinderWithMinIntPropertyError()
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

    public function testAttributeBinderWithMaxStringPropertyError()
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

    public function testAttributeBinderWithMinStringPropertyError()
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

    public function testAttributeBinderWithMaxArrayPropertyError()
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

    public function testAttributeBinderWithMinArrayPropertyError()
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

    public function testAttributeBinderWithAllNullProperties()
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

    public function testAttributeBinderWithSomeNullProperties()
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
