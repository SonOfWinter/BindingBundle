<?php
/**
 * Binder class
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\Proxy;
use Error;
use Exception;
use Psr\Log\LoggerInterface;
use SOW\BindingBundle\Exception\BinderConfigurationException;
use SOW\BindingBundle\Exception\BinderIncludeException;
use SOW\BindingBundle\Exception\BinderMaxValueException;
use SOW\BindingBundle\Exception\BinderMinValueException;
use SOW\BindingBundle\Exception\BinderNullableException;
use SOW\BindingBundle\Exception\BinderProxyClassException;
use SOW\BindingBundle\Exception\BinderRecursiveException;
use SOW\BindingBundle\Exception\BinderTypeException;
use SOW\BindingBundle\Loader\AnnotationClassLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use TypeError;

/**
 * Class Binder
 *
 * @package SOW\BindingBundle
 */
class Binder implements BinderInterface
{
    public const METHOD_ANNOTATION = "annotation";
    public const METHOD_ATTRIBUTE = "attribute";

    protected ?LoggerInterface $logger = null;

    protected mixed $resource = null;

    protected LoaderInterface $loader;

    protected ?BindingCollection $collection = null;

    private EntityManagerInterface $em;

    private int $bindingMaxRecursiveCalls;

    /**
     * Binder constructor.
     *
     * @param LoaderInterface $annotationLoader
     * @param LoaderInterface $attributeLoader
     * @param EntityManagerInterface $em
     * @param int $bindingMaxRecursiveCalls
     * @param string $method
     * @param LoggerInterface|null $logger
     *
     * @throws BinderConfigurationException
     */
    public function __construct(
        LoaderInterface $annotationLoader,
        LoaderInterface $attributeLoader,
        EntityManagerInterface $em,
        int $bindingMaxRecursiveCalls,
        string $method,
        LoggerInterface $logger = null
    ) {
        if ($method === self::METHOD_ANNOTATION) {
            $this->loader = $annotationLoader;
        } elseif ($method === self::METHOD_ATTRIBUTE) {
            $this->loader = $attributeLoader;
        } else {
            throw new BinderConfigurationException("Wrong binder method");
        }
        $this->em = $em;
        $this->bindingMaxRecursiveCalls = $bindingMaxRecursiveCalls;
        $this->logger = $logger;
    }

    /**
     * setResource
     *
     * @param $resource
     *
     * @throws Exception
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
     * @throws Exception
     */
    public function getBindingCollection(): ?BindingCollection
    {
        if ($this->resource === null) {
            throw new BinderConfigurationException();
        }
        return $this->collection;
    }

    /**
     * loadCollection
     *
     * @throws Exception
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
     * @throws BinderNullableException
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
                    } catch (TypeError $te) {
                        if ($this->logger !== null) {
                            $this->logger->error($te->getMessage());
                        } else {
                            error_log($te->getMessage());
                        }
                    } catch (Error $e) {
                        if ($this->logger !== null) {
                            $this->logger->error(get_class($e));
                            $this->logger->error($te->getMessage());
                        } else {
                            error_log(get_class($e));
                            error_log($e->getMessage());
                        }
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
                    if (!$binding->isNullable()) {
                        $this->checkNullValue($binding->getKey(), $value);
                    }
                    if (!empty($binding->getType())) {
                        $castValue = $this->checkType($binding, $value);
                        if ($castValue !== null) {
                            $value = $castValue;
                        }
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
     * @return float|null
     */
    protected function checkType(Binding $binding, $value): ?float
    {
        $valueType = gettype($value);
        $annotType = $binding->getType();
        if (AnnotationClassLoader::isScalar($annotType) && $valueType !== $annotType && $value !== null) {
            if ($annotType === 'float' && $valueType === 'double') {
                return floatval($value);
            }
            throw new BinderTypeException($annotType, $valueType, $binding->getKey());
        }
        return null;
    }

    /**
     * checkMinValue
     *
     * @param $key
     * @param $value
     * @param $min
     *
     * @throws BinderMinValueException
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

    /**
     * checkNullValue
     *
     * @param $key
     * @param $value
     *
     * @throws BinderNullableException
     * @return void
     */
    protected function checkNullValue($key, $value)
    {
        if ($value === null) {
            throw new BinderNullableException($key);
        }
    }
}
