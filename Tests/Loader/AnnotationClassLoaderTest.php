<?php

namespace SOW\BindingBundle\Tests\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

class AnnotationClassLoaderTest extends TestCase
{
    private $reader;

    private $loader;

    protected function setUp()
    {
        parent::setUp();
        $this->reader = new AnnotationReader();
        $this->loader = $this->getClassLoader($this->reader);
    }

    public function getReader()
    {
        return $this->getMockBuilder('Doctrine\Common\Annotations\Reader')->disableOriginalConstructor()->getMock();
    }

    public function getClassLoader($reader)
    {
        return $this->getMockBuilder('SOW\BindingBundle\Loader\AnnotationClassLoader')->setConstructorArgs([$reader])
                    ->getMockForAbstractClass();
    }

    # load

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoad_withWrongClass_throwsInvalidArgumentException()
    {
        $this->loader->load('WrongClass');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoad_withAbstractClass_throwsInvalidArgumentException()
    {
        $this->loader->load('SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\AbstractClass');
    }

    public function testSupportsChecksTypeIfSpecified()
    {
        $this->assertTrue($this->loader->supports('class', 'annotation'), '->supports() checks the resource type if specified');
        $this->assertFalse($this->loader->supports('class', 'foo'), '->supports() checks the resource type if specified');
    }
}