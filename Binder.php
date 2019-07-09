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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\Proxy;
use Psr\Log\LoggerInterface;
use SOW\BindingBundle\Exception\BinderConfigurationException;
use SOW\BindingBundle\Exception\BinderIncludeException;
use SOW\BindingBundle\Exception\BinderMaxValueException;
use SOW\BindingBundle\Exception\BinderMinValueException;
use SOW\BindingBundle\Exception\BinderProxyClassException;
use SOW\BindingBundle\Exception\BinderRecursiveException;
use SOW\BindingBundle\Exception\BinderTypeException;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var int
     */
    private $bindingMaxRecursiveCalls;

    /**
     * Binder constructor.
     *
     * @param LoaderInterface $loader
     * @param EntityManagerInterface $em
     * @param $bindingMaxRecursiveCalls
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        LoaderInterface $loader,
        EntityManagerInterface $em,
        $bindingMaxRecursiveCalls,
        LoggerInterface $logger = null
    ) {
        $this->loader = $loader;
        $this->em = $em;
        $this->bindingMaxRecursiveCalls = intval($bindingMaxRecursiveCalls);
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
     * @throws BinderRecursiveException
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
        /** @var Binding $binding */
        foreach ($collection as $binding) {
            $getter = $binding->getGetter();
            $setter = $binding->getSetter();
            if (AnnotationClassLoader::isNotScalar($binding->getType())) {
                $subObject = $object->$getter();
                // if sub-object not yet created, try create it with empty constructor
                if (empty($subObject)) {
                    try {
                        $type = $binding->getType();
                        $subObject = new $type();
                    } catch (\TypeError $te) {
                        error_log($te->getMessage());
                    } catch (\Error $e) {
                        error_log(get_class($e));
                        error_log($e->getMessage());
                    }
                }
                if (!empty($subObject) && array_key_exists($binding->getKey(), $params)) {
                    // get real object and replace proxy
                    if ($subObject instanceof Proxy) {
                        $realClassName = $this->em->getClassMetadata(get_class($subObject))->rootEntityName;
                        $subObject = $this->em->find($realClassName, $subObject->getId());
                        if ($subObject === null) {
                            throw new BinderProxyClassException();
                        }
                    }
                    $this->bindingMaxRecursiveCalls--;
                    if ($this->bindingMaxRecursiveCalls < 0) {
                        throw new BinderRecursiveException();
                    }
                    $this->bind($subObject, $params[$binding->getKey()]);
                    $this->bindingMaxRecursiveCalls--;
                    $object->$setter($subObject);
                    // after bind sub-object, redefine resource with parent object
                    $this->checkResource($object);
                }
            } else {
                if (array_key_exists($binding->getKey(), $params)) {
                    if (in_array($binding->getKey(), $exclude)) {
                        continue;
                    }
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
                    $object->$setter($value);
                }
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
            if ($object instanceof Proxy) {
                //throw new BinderProxyClassException();
                $this->setResource($this->em->getClassMetadata(get_class($object))->rootEntityName);
                $object->__load();
            } else {
                $this->setResource(get_class($object));
            }
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
        if (!AnnotationClassLoader::isNotScalar($annotType) && $valueType !== $annotType) {
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
