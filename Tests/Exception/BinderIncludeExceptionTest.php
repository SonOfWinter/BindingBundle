<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  ${NAMESPACE}
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Tests\Exception;

use SOW\BindingBundle\Exception\BinderIncludeException;
use PHPUnit\Framework\TestCase;

/**
 * Class BinderIncludeExceptionTest
 *
 * @package SOW\BindingBundle\Tests\Exception
 */
class BinderIncludeExceptionTest extends TestCase
{
    public function testException()
    {
        $propertyOne = 'lastname';
        $propertyTwo = 'firstname';
        $properties = [$propertyOne, $propertyTwo];
        $message = sprintf(BinderIncludeException::MESSAGE, implode(', ', $properties));
        $exception = new BinderIncludeException($properties);
        $this->assertEquals(BinderIncludeException::CODE, $exception->getCode());
        $this->assertEquals($properties, $exception->getMissingKeys());
        $this->assertEquals($message, $exception->getMessage());
    }
}
