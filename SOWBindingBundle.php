<?php

/**
 * Binding Bundle
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

use SOW\BindingBundle\DependencyInjection\SOWBindingExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SOWBindingBundle
 *
 * @package SOW\BindingBundle
 */
class SOWBindingBundle extends Bundle
{
    /**
     * @return null|SOWBindingExtension|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new SOWBindingExtension();
    }
}
