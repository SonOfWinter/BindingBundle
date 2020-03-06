<?php

/**
 * Configuration test
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Tests\DependencyInjection
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Tests\DependencyInjection;

use SOW\BindingBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 *
 * @package SOW\BindingBundle\Tests\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    public function testConfiguration()
    {
        $configuration = new Configuration();
        $tree = $configuration->getConfigTreeBuilder();
        $this->assertTrue($tree instanceof TreeBuilder);
    }
}
