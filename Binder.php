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
use SOW\BindingBundle\Exception\BinderMaxValueException;
use SOW\BindingBundle\Exception\BinderMinValueException;
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
     * @throws BinderMaxValueException
     * @throws BinderMinValueException
     *
     * @return void
     */
    public function bind(&$object, array $params = [], array $include = [], array $exclude = [])
    {
        $this->checkResource($object);
        $includeCount = count($include);
        $includeIntersect = count(array_intersect($include, array_keys($params)));
        if ($includeCount !== $includeIntersect) {
            throw new BinderIncludeException(array_diff($include, array_keys($params)));
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
                    $this->checkType($binding, $value);
                }
                if ($binding->getMin() !== null) {
                    $this->checkMinValue($binding->getKey(), $value, $binding->getMin());
                }
                if ($binding->getMax() !== null) {
                    $this->checkMaxValue($binding->getKey(), $value, $binding->getMax());
                }
                $object->$method($value);
            }
        }
    }

    /**
     * getKeys
     *
     * @param $object
     *
     * @throws BinderConfigurationException
     * @throws BinderProxyClassException
     *
     * @return array
     */
    public function getKeys($object): array
    {
        $this->checkResource($object);
        $collection = $this->getBindingCollection();
        $bindings = $collection->all();
        $getKey = function (Binding $binding) {
            return $binding->getKey();
        };
        return array_map($getKey, $bindings);
    }

    /**
     * checkResource
     *
     * @param $object
     *
     * @throws BinderProxyClassException
     *
     * @return void
     */
    protected function checkResource($object)
    {
        if ($this->resource !== get_class($object)) {
            $this->setResource(get_class($object));
        }
        if (strpos($this->resource, Proxy::MARKER) !== false) {
            throw new BinderProxyClassException();
        }
    }

    /**
     * checkType
     *
     * @param Binding $binding
     * @param $value
     *
     * @throws BinderTypeException
     *
     * @return void
     */
    protected function checkType(Binding $binding, $value)
    {
        $valueType = gettype($value);
        $annotType = $binding->getType();
        if ($valueType !== $annotType) {
            throw new BinderTypeException($annotType, $valueType, $binding->getKey());
        }
    }

    /**
     * checkMinValue
     *
     * @param $key
     * @param $value
     * @param $min
     *
     * @throws BinderMinValueException
     *
     * @return void
     */
    protected function checkMinValue($key, $value, $min)
    {
        if (is_string($value)) {
            if (strlen($value) < $min) {
                throw new BinderMinValueException($key, $min);
            }
        } elseif (is_numeric($value)) {
            if ($value < $min) {
                throw new BinderMinValueException($key, $min);
            }
        } elseif (is_iterable($value)) {
            if (count($value) < $min) {
                throw new BinderMinValueException($key, $min);
            }
        }
    }

    /**
     * checkMaxValue
     *
     * @param $key
     * @param $value
     * @param $max
     *
     * @throws BinderMaxValueException
     *
     * @return void
     */
    protected function checkMaxValue($key, $value, $max)
    {
        if (is_string($value)) {
            if (strlen($value) > $max) {
                throw new BinderMaxValueException($key, $max);
            }
        } elseif (is_numeric($value)) {
            if ($value > $max) {
                throw new BinderMaxValueException($key, $max);
            }
        } elseif (is_iterable($value)) {
            if (count($value) > $max) {
                throw new BinderMaxValueException($key, $max);
            }
        }
    }
}
