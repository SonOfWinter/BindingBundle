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

use SOW\BindingBundle\Exception\BinderProxyClassException;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class BinderProxyClassExceptionTest
 *
 * @package SOW\BindingBundle\Tests\Exception
 */
class BinderProxyClassExceptionTest extends TestCase
{
    public function testException()
    {
        $exception = new BinderProxyClassException();
        $this->assertEquals(BinderProxyClassException::CODE, $exception->getCode());
        $this->assertEquals(BinderProxyClassException::MESSAGE, $exception->getMessage());
    }
}