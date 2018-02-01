<?php

namespace SOW\BindingBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Config\Resource\FileResource;

class BinderTest extends TestCase
{
    public function testBinder()
    {
        $rightFakeArray = [
            'lastname'  => 'Bullock',
            'firstname' => 'Ryan'
        ];
        $testObject = new TestObject();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->bind($testObject, $rightFakeArray);
        $this->assertEquals($rightFakeArray['lastname'], $testObject->getLastname());
        $this->assertEquals($rightFakeArray['firstname'], $testObject->getFirstname());
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
}