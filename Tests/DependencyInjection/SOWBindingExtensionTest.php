<?php
/**
 * @package  SOW\BindingBundle\Tests\DependencyInjection
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace SOW\BindingBundle\Tests\DependencyInjection;

use SOW\BindingBundle\DependencyInjection\Configuration;
use SOW\BindingBundle\DependencyInjection\SOWBindingExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SOWBindingExtensionTest
 *
 * @package SOW\BindingBundle\Tests\DependencyInjection
 */
class SOWBindingExtensionTest extends TestCase
{
    public function testLoad()
    {
        $paramBag = $this->createMock(ParameterBagInterface::class);
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('getReflectionClass')
            ->will($this->returnValue(new \ReflectionClass(Configuration::class)));
        $container->expects($this->any())
            ->method('getParameterBag')
            ->will($this->returnValue($paramBag));
        $container->expects($this->exactly(4))
            ->method('setParameter')
            ->will($this->returnValue($paramBag));
        $container->expects($this->once())
            ->method('fileExists')
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('setAlias');
        $extension = new SOWBindingExtension();
        $extension->load([], $container);
    }
}
