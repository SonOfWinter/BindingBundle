<?php
/**
 * Bundle Extension class
 *
 * @package  SOW\BindingBundle\DependencyInjection
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\DependencyInjection;

use SOW\BindingBundle\BinderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class SOWBindingExtension
 *
 * @package SOW\BindingBundle\DependencyInjection
 */
class SOWBindingExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     * @throws LogicException
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $this->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
        $container->setAlias(
            BinderInterface::class,
            new Alias('sow_binding.binder')
        );
    }
}
