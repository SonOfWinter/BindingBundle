<?php

/**
 * Binder test
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Exception\BinderMaxValueException;
use SOW\BindingBundle\Exception\BinderMinValueException;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses\ProxyTestObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedMinMaxObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class BinderTest
 *
 * @package SOW\BindingBundle\Tests
 */
class BinderTest extends TestCase
{
    private $bindingAnnotationClass = 'SOW\\BindingBundle\\Annotation\\Binding';

    public function testBinderWithAllProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com',
            'age' => 25
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertEquals(
            $dataArray['firstname'],
            $testObject->getFirstname()
        );
        $this->assertEquals(
            $dataArray['userEmail'],
            $testObject->getUserEmail()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    public function testBinderWithOneProperty()
    {
        $dataArray = [
            'lastname' => 'Bullock'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertEquals(
            null,
            $testObject->getFirstname()
        );
        $this->assertEquals(
            null,
            $testObject->getUserEmail()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    /**
     * @expectedException SOW\BindingBundle\Exception\BinderConfigurationException
     */
    public function testGetCollectionWithoutResource()
    {
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->getBindingCollection();
    }

    public function testGetCollectionWithResourceAndCollection()
    {
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->setResource(get_class($testObject));
        $collection = $bindingService->getBindingCollection();
        $this->assertEquals(
            $collection,
            $bindingService->getBindingCollection()
        );
    }

    public function testBinderWithAllTypedProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 25
        ];
        $testObject = new TestTypedObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertEquals(
            $dataArray['firstname'],
            $testObject->getFirstname()
        );
        $this->assertEquals(
            $dataArray['age'],
            $testObject->getAge()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    /**
     * @expectedException SOW\BindingBundle\Exception\BinderTypeException
     * @expectedExceptionMessage Wrong firstname parameter type. Expected : string, received : double
     */
    public function testBinderWithWrongTypedProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 5.7,
            'age' => true
        ];
        $testObject = new TestTypedObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray
        );
    }


    /**
     * @expectedException SOW\BindingBundle\Exception\BinderProxyClassException
     * @expectedExceptionMessage Don't use Doctrine Proxy class with Binder
     */
    public function testBinderWithProxyResource()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 5.7,
            'age' => true
        ];
        $testObject = new ProxyTestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray
        );
    }

    public function testBinderWithExcludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray,
            [],
            ['firstname']
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertNull($testObject->getFirstname());
        $this->assertEquals(
            $dataArray['userEmail'],
            $testObject->getUserEmail()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    public function testBinderWithWrongExcludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray,
            [],
            ['wrongValue']
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertEquals(
            $dataArray['firstname'],
            $testObject->getFirstname()
        );
        $this->assertEquals(
            $dataArray['userEmail'],
            $testObject->getUserEmail()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    public function testBinderWithIncludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray,
            ['lastname', 'firstname']
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertEquals(
            $dataArray['firstname'],
            $testObject->getFirstname()
        );
        $this->assertEquals(
            $dataArray['userEmail'],
            $testObject->getUserEmail()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    /**
     * @expectedException SOW\BindingBundle\Exception\BinderIncludeException
     * @expectedExceptionMessage Missing mandatory keys : phone
     */
    public function testBinderWithMissingIncludeProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray,
            ['lastname', 'firstname', 'phone']
        );
    }

    public function testGetKeys()
    {
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $result = $bindingService->getKeys($testObject);
        $this->assertEquals(3, count($result));
        $this->assertTrue(in_array('lastname', $result));
        $this->assertTrue(in_array('firstname', $result));
        $this->assertTrue(in_array('userEmail', $result));
    }


    public function testBinderWithAllTypedAndMinMaxProperties()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 25,
            'letterList' => ['a', 'b', 'c']
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        $bindingService->bind(
            $testObject,
            $dataArray
        );
        $this->assertEquals(
            $dataArray['lastname'],
            $testObject->getLastname()
        );
        $this->assertEquals(
            $dataArray['firstname'],
            $testObject->getFirstname()
        );
        $this->assertEquals(
            $dataArray['age'],
            $testObject->getAge()
        );
        $this->assertEquals(
            $dataArray['letterList'],
            $testObject->getLetterList()
        );
        $this->assertEquals(
            null,
            $testObject->getNotBindProperty()
        );
    }

    public function testBinderWithMaxIntPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 125,
            'letterList' => ['a', 'b', 'c']
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        try {
            $bindingService->bind(
                $testObject,
                $dataArray
            );
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(100, $e->getMax());
            $this->assertEquals('age', $e->getKey());
            $this->assertEquals('age must have a value less than : 100', $e->getMessage());
        }
    }

    public function testBinderWithMinIntPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => -25,
            'letterList' => ['a', 'b', 'c']
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        try {
            $bindingService->bind(
                $testObject,
                $dataArray
            );
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(0, $e->getMin());
            $this->assertEquals('age', $e->getKey());
            $this->assertEquals('age must have a value more than : 0', $e->getMessage());
        }
    }

    public function testBinderWithMaxStringPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryanapoiutonugindpoad',
            'age' => 20,
            'letterList' => ['a', 'b', 'c']
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        try {
            $bindingService->bind(
                $testObject,
                $dataArray
            );
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(20, $e->getMax());
            $this->assertEquals('firstname', $e->getKey());
            $this->assertEquals('firstname must have a value less than : 20', $e->getMessage());
        }
    }

    public function testBinderWithMinStringPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'a',
            'age' => 20,
            'letterList' => ['a', 'b', 'c']
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        try {
            $bindingService->bind(
                $testObject,
                $dataArray
            );
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(2, $e->getMin());
            $this->assertEquals('firstname', $e->getKey());
            $this->assertEquals('firstname must have a value more than : 2', $e->getMessage());
        }
    }


    public function testBinderWithMaxArrayPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 20,
            'letterList' => ['a', 'b', 'c', 'd', 'e']
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        try {
            $bindingService->bind(
                $testObject,
                $dataArray
            );
            $this->fail('BinderMaxValueException must be throw');
        } catch (BinderMaxValueException $e) {
            $this->assertEquals(3, $e->getMax());
            $this->assertEquals('letterList', $e->getKey());
            $this->assertEquals('letterList must have a value less than : 3', $e->getMessage());
        }
    }

    public function testBinderWithMinArrayPropertyError()
    {
        $dataArray = [
            'lastname' => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 20,
            'letterList' => []
        ];
        $testObject = new TestTypedMinMaxObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader, $this->bindingAnnotationClass);
        $bindingService = new Binder($loader);
        try {
            $bindingService->bind(
                $testObject,
                $dataArray
            );
            $this->fail('BinderMinValueException must be throw');
        } catch (BinderMinValueException $e) {
            $this->assertEquals(1, $e->getMin());
            $this->assertEquals('letterList', $e->getKey());
            $this->assertEquals('letterList must have a value more than : 1', $e->getMessage());
        }
    }
}
