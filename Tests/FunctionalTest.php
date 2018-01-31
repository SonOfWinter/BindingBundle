<?php

namespace SOW\BindingBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FunctionalTest extends TestCase
{
    public function testGetCorrespondenceArrayWithFakeObject()
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
}