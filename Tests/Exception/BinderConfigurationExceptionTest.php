<?php
/**
 * @package  SOW\BindingBundle\Tests\Exception
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Tests\Exception;

use SOW\BindingBundle\Exception\BinderConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * Class BinderConfigurationExceptionTest
 *
 * @package SOW\BindingBundle\Tests\Exception
 */
class BinderConfigurationExceptionTest extends TestCase
{
    public function testException()
    {
        $exception = new BinderConfigurationException();
        $this->assertEquals(BinderConfigurationException::CODE, $exception->getCode());
        $this->assertEquals(BinderConfigurationException::MESSAGE, $exception->getMessage());
    }
}
