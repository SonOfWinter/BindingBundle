<?php
/**
 * Binder Interface
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

use Exception;
use SOW\BindingBundle\Exception\BinderConfigurationException;
use SOW\BindingBundle\Exception\BinderException;

/**
 * Interface BinderInterface
 *
 * @package  SOW\BindingBundle
 */
interface BinderInterface
{
    /**
     * @throws BinderConfigurationException
     * @throws Exception
     */
    public function getBindingCollection(): ?BindingCollection;

    /**
     * bind an array to entity
     *
     * @param       $object
     * @param array $params
     * @param array $include
     * @param array $exclude
     *
     * @throws BinderException
     * @return void
     */
    public function bind(
        &$object,
        array $params = [],
        array $include = [],
        array $exclude = []
    ): void;

    /**
     * getKeys
     *
     * @param $object
     *
     * @return array
     */
    public function getKeys($object): array;
}
