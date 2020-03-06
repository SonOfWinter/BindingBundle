<?php

/**
 * BinderBundle test
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Tests
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests;

use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Class BindingCollectionTest
 *
 * @package SOW\BindingBundle\Tests
 */
class BindingCollectionTest extends TestCase
{
    public function testBinding()
    {
        $collection = new BindingCollection();
        $binding = new Binding(
            'firstname',
            'setFirstname'
        );
        $collection->addBinding($binding);
        $this->assertEquals(
            ['firstname' => $binding],
            $collection->all()
        );
        $this->assertEquals(
            $binding,
            $collection->get('firstname')
        );
        $this->assertNull($collection->get('foo'));
    }

    public function testOverrinddenBinding()
    {
        $collection = new BindingCollection();
        $collection->addBinding(
            new Binding(
                'firstname',
                'setFirstname'
            )
        );
        $collection->addBinding(
            new Binding(
                'firstname',
                'setLastname'
            )
        );
        $this->assertEquals(
            'setLastname',
            $collection->get('firstname')->getSetter()
        );
    }

    public function testCount()
    {
        $collection = new BindingCollection();
        $collection->addBinding(
            new Binding(
                'firstname',
                'setFirstname'
            )
        );
        $collection->addBinding(
            new Binding(
                'lastname',
                'setLastname'
            )
        );
        $this->assertEquals(
            2,
            $collection->count()
        );
    }

    public function testRemove()
    {
        $collection = new BindingCollection();
        $collection->addBinding(
            $binding1 = new Binding(
                'firstname',
                'setFirstname'
            )
        );
        $collection->addBinding(
            $binding2 = new Binding(
                'lastname',
                'setLastname'
            )
        );
        $collection->remove('firstname');
        $this->assertEquals(
            ['lastname' => $binding2],
            $collection->all()
        );
        $this->assertEquals(
            1,
            $collection->count()
        );
    }

    public function testMergeCollection()
    {
        $collection = new BindingCollection();
        $collection->addBinding(
            $b1 = new Binding(
                'firstname',
                'setFirstname'
            )
        );
        $collection->addResource(
            $r1 = new FileResource(
                __DIR__ . '/Fixtures/AnnotatedClasses/AbstractClass.php'
            )
        );


        $collection1 = new BindingCollection();
        $collection1->addBinding(
            $b2 = new Binding(
                'lastname',
                'setLastname'
            )
        );
        $collection1->addBinding(
            $b3 = new Binding(
                'firstname',
                'setFirstname'
            )
        );
        $collection1->addResource(
            $r2 = new FileResource(
                __DIR__ . '/Fixtures/AnnotatedClasses/TestObject.php'
            )
        );

        $collection2 = new BindingCollection();
        $collection2->addBinding(
            $b4 = new Binding(
                'email',
                'setEmail'
            )
        );
        $collection2->addResource($r2);

        $collection1->mergeCollection($collection2);
        $collection->mergeCollection($collection1);

        $collection->addBinding(
            $b5 = new Binding(
                'username',
                'setUsername'
            )
        );

        $this->assertSame(
            [
                'lastname' => $b2,
                'firstname' => $b3,
                'email' => $b4,
                'username' => $b5
            ],
            $collection->all()
        );
        $this->assertSame(
            [$r1, $r2],
            $collection->getResources()
        );
    }

    public function testResource()
    {
        $collection = new BindingCollection();
        $collection->addBinding(
            new Binding(
                'firstname',
                'setFirstname'
            )
        );
        $collection->addResource(
            $r1 = new FileResource(
                __DIR__ . '/Fixtures/AnnotatedClasses/AbstractClass.php'
            )
        );
        $collection->addResource(
            $r2 = new FileResource(
                __DIR__ . '/Fixtures/AnnotatedClasses/TestObject.php'
            )
        );
        $collection->addResource(
            new FileResource(
                __DIR__ . '/Fixtures/AnnotatedClasses/AbstractClass.php'
            )
        );
        $this->assertSame(
            [$r1, $r2],
            $collection->getResources()
        );
    }
}
