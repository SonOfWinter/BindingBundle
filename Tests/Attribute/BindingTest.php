<?php
/**
 * Binding test
 *
 * @package  SOW\BindingBundle\Tests\Attribute
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use SOW\BindingBundle\Attribute\Binding;

/**
 * Class BindingTest
 *
 * @package SOW\BindingBundle\Tests\Attribute
 */
class BindingTest extends TestCase
{
    public function testValidNameSetterAndTypeParameters(): void
    {
        $binding = new Binding(
            key:    'test',
            setter: 'getTest',
            type:   'string'
        );
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertEquals('getTest', $binding->getSetter());
        $this->assertEquals('string', $binding->getType());
    }

    public function testValidNameAndSetterParameters(): void
    {
        $binding = new Binding('test', 'getTest');
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertEquals('getTest', $binding->getSetter());
        $this->assertNull($binding->getType());
    }

    public function testValidNameParameter(): void
    {
        $binding = new Binding('test');
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertEquals('setTest', $binding->getSetter());
        $this->assertNull($binding->getType());
    }

    public function testValidMinMaxAndNullableParameters(): void
    {
        $binding = new Binding(
            key:      'test',
            min:      1,
            max:      10,
            nullable: true
        );
        $this->assertTrue($binding instanceof Binding);
        $this->assertEquals('test', $binding->getKey());
        $this->assertTrue($binding->isNullable());
        $this->assertEquals(1, $binding->getMin());
        $this->assertEquals(10, $binding->getMax());
    }
}
