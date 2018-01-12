<?php

namespace SOW\BindingBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use SOW\BindingBundle\Binder;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class BindingServiceTest extends TestCase
{
    public function testGetCorrespondenceArrayWithFakeObject()
    {
        $rightFakeArray = [
            'lastname'  => 'Bullock',
            'firstname' => 'Ryan'
        ];
        $fakeObject = new Fake();
        $reader = new AnnotationReader();
        $loader = new AnnotationClassLoader($reader);
        $bindingService = new Binder($loader);
        $bindingService->bind($fakeObject, $rightFakeArray);
        $this->assertEquals($rightFakeArray['lastname'], $fakeObject->getLastname());
        $this->assertEquals($rightFakeArray['firstname'], $fakeObject->getFirstname());
        $this->assertEquals(null, $fakeObject->getNotBindProperty());
    }
}