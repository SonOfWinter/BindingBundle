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

use SOW\BindingBundle\Exception\BinderConfigurationException;

/**
 * Interface BinderInterface
 *
 * @package  SOW\BindingBundle
 */
interface BinderInterface
{
    /**
     * @throws BinderConfigurationException
     * @throws \Exception
     *
     * @return null|BindingCollection
     */
    public function getBindingCollection();

    /**
     * bind an array to entity
     *
     * @param       $object
     * @param array $params
     * @param array $include
     * @param array $exclude
     *
     * @return void
     */
    public function bind(&$object, array $params = [], array $include = [], array $exclude = []);
}
