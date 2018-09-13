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
use SOW\BindingBundle\Exception\BinderTypeException;

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
     *
     * @throws BinderConfigurationException
     * @throws BinderTypeException
     *
     * @return void
     */
    public function bind(&$object, array $params = []);
}
