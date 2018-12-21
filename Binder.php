<?php

/**
 * Binder class
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

use Doctrine\ORM\Proxy\Proxy;
use Psr\Log\LoggerInterface;
use SOW\BindingBundle\Exception\BinderConfigurationException;
use SOW\BindingBundle\Exception\BinderIncludeException;
use SOW\BindingBundle\Exception\BinderProxyClassException;
use SOW\BindingBundle\Exception\BinderTypeException;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class Binder
 *
 * @package SOW\BindingBundle
 */
class Binder implements BinderInterface
{
    /**
     * @var LoggerInterface|null
     */
    protected $logger;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @var BindingCollection|null
     */
    protected $collection;

    /**
     * Binder constructor.
     *
     * @param LoaderInterface $loader
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoaderInterface $loader, LoggerInterface $logger = null)
    {
        $this->loader = $loader;
        $this->logger = $logger;
    }

    /**
     * setResource
     *
     * @param $resource
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        $this->loadCollection();
    }

    /**
     * getBindingCollection
     *
     * @throws BinderConfigurationException
     * @throws \Exception
     *
     * @return null|BindingCollection
     */
    public function getBindingCollection()
    {
        if ($this->resource === null) {
            throw new BinderConfigurationException();
        }
        return $this->collection;
    }

    /**
     * loadCollection
     *
     * @throws \Exception
     *
     * @return null|BindingCollection
     */
    private function loadCollection()
    {
        $this->collection = $this->loader->load($this->resource, 'annotation');
        return $this->collection;
    }

    /**
     * bind an array to entity
     *
     * @param       $object
     * @param array $params
     * @param array $include
     * @param array $exclude
     *
     * @throws BinderConfigurationException
     * @throws BinderProxyClassException
     * @throws BinderTypeException
     * @throws BinderIncludeException
     *
     * @return void
     */
    public function bind(&$object, array $params = [], array $include = [], array $exclude = [])
    {
        if ($this->resource !== get_class($object)) {
            $this->setResource(get_class($object));
        }
        if (strpos($this->resource, Proxy::MARKER) !== false) {
            throw new BinderProxyClassException();
        }
        $includeCount = count($include);
        $includeIntersect = count(array_intersect($include, array_keys($params)));
        if ($includeCount !== $includeIntersect) {
            throw new BinderIncludeException();
        }
        $collection = $this->getBindingCollection();
        foreach ($collection as $binding) {
            if (array_key_exists($binding->getKey(), $params)) {
                if (in_array($binding->getKey(), $exclude)) {
                    continue;
                }
                $method = $binding->getSetter();
                $value = $params[$binding->getKey()];
                if (!empty($binding->getType())) {
                    $valueType = gettype($value);
                    $annotType = $binding->getType();
                    if ($valueType !== $annotType) {
                        throw new BinderTypeException($annotType, $valueType);
                    }
                }
                $object->$method($value);
            }
        }
    }
}
