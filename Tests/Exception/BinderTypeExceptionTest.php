<?php

/**
 * @package  SOW\BindingBundle\Tests\Exception
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Tests\Exception;

use SOW\BindingBundle\Exception\BinderTypeException;
use PHPUnit\Framework\TestCase;

/**
 * Class BinderTypeExceptionTest
 *
 * @package SOW\BindingBundle\Tests\Exception
 */
class BinderTypeExceptionTest extends TestCase
{
    public function testException()
    {
        $property = 'lastname';
        $typeExpected = 'string';
        $typeReceived = 'int';
        $message = sprintf(
            BinderTypeException::MESSAGE,
            $property,
            $typeExpected,
            $typeReceived
        );
        $exception = new BinderTypeException($typeExpected, $typeReceived, $property);
        $this->assertEquals(BinderTypeException::CODE, $exception->getCode());
        $this->assertEquals($property, $exception->getProperty());
        $this->assertEquals($typeExpected, $exception->getTypeExpected());
        $this->assertEquals($typeReceived, $exception->getTypeReceived());
        $this->assertEquals($message, $exception->getMessage());
    }
}
