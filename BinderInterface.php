<?php

/**
 * Binder Interface
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

/**
 * Interface BinderInterface
 *
 * @package  SOW\BindingBundle
 */
interface BinderInterface
{
    public function getBindingCollection();

    public function bind(&$object, array $params = []);
}
