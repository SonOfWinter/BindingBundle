<?php

namespace SOW\BindingBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestTypedObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class BinderTest extends TestCase
{
    public function testBinderWithAllProperties()
    {
        $dataArray = [
            'lastname'  => 'Bullock',
            'firstname' => 'Ryan'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals(null, $testObject->getNotBindProperty());
    }

    public function testBinderWithOneProperty()
    {
        $dataArray = [
            'lastname'  => 'Bullock'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals(null, $testObject->getFirstname());
        $this->assertEquals(null, $testObject->getNotBindProperty());
    }

    /**
     * @expectedException SOW\BindingBundle\Exception\BinderConfigurationException
     */
    public function testGetCollectionWithoutResource()
    {
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->getBindingCollection();
    }

    public function testGetCollectionWithResourceAndCollection()
    {
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->setResource(get_class($testObject));
        $collection = $bindingService->getBindingCollection();
        $this->assertEquals($collection, $bindingService->getBindingCollection());
    }

    public function testBinderWithAllTypedProperties()
    {
        $dataArray = [
            'lastname'  => 'Bullock',
            'firstname' => 'Ryan',
            'age' => 24
        ];
        $testObject = new TestTypedObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->bind($testObject, $dataArray);
        $this->assertEquals($dataArray['lastname'], $testObject->getLastname());
        $this->assertEquals($dataArray['firstname'], $testObject->getFirstname());
        $this->assertEquals($dataArray['age'], $testObject->getAge());
        $this->assertEquals(null, $testObject->getNotBindProperty());
    }

    /**
     * @expectedException SOW\BindingBundle\Exception\BinderTypeException
     * @expectedExceptionMessage Wrong value type. Expected : string, received : double
     */
    public function testBinderWithWrongTypedProperties()
    {
        $dataArray = [
            'lastname'  => 'Bullock',
            'firstname' => 5.7,
            'age' => true
        ];
        $testObject = new TestTypedObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->bind($testObject, $dataArray);
    }
}