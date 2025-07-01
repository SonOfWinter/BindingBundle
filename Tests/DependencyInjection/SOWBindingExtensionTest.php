<?php

namespace SOW\BindingBundle\Tests\DependencyInjection;

use Exception;
use ReflectionClass;
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
    public function testLoad(): void
    {
        try {
            $paramBag = $this->createMock(ParameterBagInterface::class);
        } catch (Exception $exception) {
            print_r($exception->getMessage());
            self::fail();
        }

        $container = $this->createMock(ContainerBuilder::class);
        $container->expects($this->once())
            ->method('getReflectionClass')
            ->will($this->returnValue(new ReflectionClass(Configuration::class)));
        $container->expects($this->any())
            ->method('getParameterBag')
            ->will($this->returnValue($paramBag));
        $container->expects($this->exactly(3))
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
