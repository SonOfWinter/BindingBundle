<?php

namespace SOW\BindingBundle\Tests;

use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Config\Resource\FileResource;

class BindingCollectionTest extends TestCase
{
    public function testBinding()
    {
        $collection = new BindingCollection();
        $binding = new Binding('firstname', 'setFirstname');
        $collection->add($binding);
        $this->assertEquals(['firstname' => $binding], $collection->all());
        $this->assertEquals($binding, $collection->get('firstname'));
        $this->assertNull($collection->get('foo'));
    }

    public function testOverrinddenBinding()
    {
        $collection = new BindingCollection();
        $collection->add(new Binding('firstname', 'setFirstname'));
        $collection->add(new Binding('firstname', 'setLastname'));
        $this->assertEquals('setLastname', $collection->get('firstname')->getSetter());
    }

    public function testCount()
    {
        $collection = new BindingCollection();
        $collection->add(new Binding('firstname', 'setFirstname'));
        $collection->add(new Binding('lastname', 'setLastname'));
        $this->assertEquals(2, $collection->count());
    }

    public function testRemove()
    {
        $collection = new BindingCollection();
        $collection->add($binding1 = new Binding('firstname', 'setFirstname'));
        $collection->add($binding2 = new Binding('lastname', 'setLastname'));
        $collection->remove('firstname');
        $this->assertEquals(['lastname' => $binding2], $collection->all());
        $this->assertEquals(1, $collection->count());
    }

    public function testAddCollection()
    {
        $collection = new BindingCollection();
        $collection->add($b1 = new Binding('firstname', 'setFirstname'));
        $collection->addResource($r1 = new FileResource(__DIR__.'/Fixtures/AnnotatedClasses/AbstractClass.php'));


        $collection1 = new BindingCollection();
        $collection1->add($b2 = new Binding('lastname', 'setLastname'));
        $collection1->add($b3 = new Binding('firstname', 'setFirstname'));
        $collection1->addResource($r2 = new FileResource(__DIR__.'/Fixtures/AnnotatedClasses/TestObject.php'));

        $collection2 = new BindingCollection();
        $collection2->add($b4 = new Binding('email', 'setEmail'));
        $collection2->addResource($r2);

        $collection1->addCollection($collection2);
        $collection->addCollection($collection1);

        $collection->add($b5 = new Binding('username', 'setUsername'));

        $this->assertSame(['lastname'  => $b2,
                           'firstname' => $b3,
                           'email'     => $b4,
                           'username'  => $b5
        ],
            $collection->all());
        $this->assertSame([$r1,$r2],
            $collection->getResources());
    }

    public function testResource()
    {
        $collection = new BindingCollection();
        $collection->add(new Binding('firstname', 'setFirstname'));
        $collection->addResource($r1 = new FileResource(__DIR__.'/Fixtures/AnnotatedClasses/AbstractClass.php'));
        $collection->addResource($r2 = new FileResource(__DIR__.'/Fixtures/AnnotatedClasses/TestObject.php'));
        $collection->addResource(new FileResource(__DIR__.'/Fixtures/AnnotatedClasses/AbstractClass.php'));
        $this->assertSame([$r1,$r2],
            $collection->getResources());
    }
}