<?php

namespace SOW\BindingBundle\Tests\Utils;

use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Utils\TypeUtils;

class TypeUtilsTest extends TestCase
{
    public function testIsNotScalarReturnsTrueForNonScalarTypes(): void
    {
        $this->assertTrue(TypeUtils::isNotScalar('object'));
        $this->assertTrue(TypeUtils::isNotScalar('DateTime'));
        $this->assertTrue(TypeUtils::isNotScalar('SomeClass'));
    }

    public function testIsNotScalarReturnsFalseForScalarTypes(): void
    {
        $this->assertFalse(TypeUtils::isNotScalar('integer'));
        $this->assertFalse(TypeUtils::isNotScalar('float'));
        $this->assertFalse(TypeUtils::isNotScalar('string'));
        $this->assertFalse(TypeUtils::isNotScalar('boolean'));
        $this->assertFalse(TypeUtils::isNotScalar('array'));
    }

    public function testIsNotScalarReturnsFalseForNullOrEmpty(): void
    {
        $this->assertFalse(TypeUtils::isNotScalar(null));
        $this->assertFalse(TypeUtils::isNotScalar(''));
    }

    public function testIsScalarReturnsTrueForScalarTypes(): void
    {
        $this->assertTrue(TypeUtils::isScalar('integer'));
        $this->assertTrue(TypeUtils::isScalar('float'));
        $this->assertTrue(TypeUtils::isScalar('string'));
        $this->assertTrue(TypeUtils::isScalar('boolean'));
        $this->assertTrue(TypeUtils::isScalar('array'));
    }

    public function testIsScalarReturnsFalseForNonScalarTypes(): void
    {
        $this->assertFalse(TypeUtils::isScalar('object'));
        $this->assertFalse(TypeUtils::isScalar('DateTime'));
        $this->assertFalse(TypeUtils::isScalar('SomeClass'));
    }

    public function testIsScalarReturnsTrueForNullOrEmpty(): void
    {
        $this->assertTrue(TypeUtils::isScalar(null));
        $this->assertTrue(TypeUtils::isScalar(''));
    }
}
