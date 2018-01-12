<?php

namespace SOW\BindingBundle;

use SOW\BindingBundle\DependencyInjection\SOWBindingExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Thomas LEDUC <thomaslmoi15@hotmail.Fr>
 */
class SOWBindingBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SOWBindingExtension();
    }
}
