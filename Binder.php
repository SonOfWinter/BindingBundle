<?php

namespace SOW\BindingBundle;

use Psr\Log\LoggerInterface;
use SOW\BindingBundle\Exception\BinderConfigurationException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Class Binder
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
     * @param LoaderInterface      $loader
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoaderInterface $loader, LoggerInterface $logger = null)
    {
        $this->loader = $loader;
        $this->logger = $logger;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return null|BindingCollection
     * @throws \Exception
     */
    public function getBindingCollection()
    {
        if ($this->resource === null) {
            throw new BinderConfigurationException();
        }
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, 'annotation');
        }
        return $this->collection;
    }

    /**
     * bind an array to entity
     * @param       $object
     * @param array $params
     * @throws \Exception
     */
    public function bind(&$object, array $params = [])
    {
        if ($this->resource === null) {
            $this->setResource(get_class($object));
        }
        $collection = $this->getBindingCollection();
        foreach ($collection as $binding) {
            if (array_key_exists($binding->getKey(), $params)) {
                $method = $binding->getSetter();
                $value = $params[$binding->getKey()];
                $object->$method($value);
            }
        }
    }
}