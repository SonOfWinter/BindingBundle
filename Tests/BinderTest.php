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
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses\ProxyTestObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
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
            'lastname'  => 'Bullock',
            'firstname' => 'Ryan',
            'userEmail' => 'r.bullock@mail.com'
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
            'lastname'  => 'Bullock',
            'firstname' => 'Ryan',
            'age'       => 24
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
            'lastname'  => 'Bullock',
            'firstname' => 5.7,
            'age'       => true
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
            'lastname'  => 'Bullock',
            'firstname' => 5.7,
            'age'       => true
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
            'lastname'  => 'Bullock',
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
            'lastname'  => 'Bullock',
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
            'lastname'  => 'Bullock',
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
            'lastname'  => 'Bullock',
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
}
