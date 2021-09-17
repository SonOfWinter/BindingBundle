<?php
/**
 * Attribute class loader
 *
 * @package  SOW\BindingBundle\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Loader;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\Proxy;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Class AttributeClassLoader
 *
 * @package SOW\BindingBundle\Loader
 */
class AttributeClassLoader implements LoaderInterface
{
    public const SCALAR_TYPES = ['integer', 'float', 'string', 'boolean', 'array'];

    protected string $bindingAttributeClass;

    private EntityManagerInterface $em;

    /**
     * AttributeClassLoader constructor.
     *
     * @param EntityManagerInterface $em
     * @param $bindingAnnotationClass
     */
    public function __construct(EntityManagerInterface $em, $bindingAnnotationClass)
    {
        $this->em = $em;
        $this->bindingAttributeClass = $bindingAnnotationClass;
    }

    /**
     * Sets the annotation class to read binding properties from.
     *
     * @param $class
     *
     * @return void
     */
    public function setBindingAttributeClass($class)
    {
        $this->bindingAttributeClass = $class;
    }

    /**
     * Load Binding data from class
     *
     * @param mixed $class
     * @param null $type
     *
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @return BindingCollection
     */
    public function load($class, $type = null): BindingCollection
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Class "%s" does not exist.',
                    $class
                )
            );
        }
        if (strpos($class, Proxy::MARKER) !== false) {
            $class = $this->em->getClassMetadata($class)->rootEntityName;
        }
        $class = new ReflectionClass($class);
        if ($class->isAbstract()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Annotations from class "%s" cannot be read as it is abstract.',
                    $class->getName()
                )
            );
        }
        $collection = new BindingCollection();
        $collection->addResource(new FileResource($class->getFileName()));
        $methods = [];
        foreach ($class->getMethods() as $reflectionMethod) {
            $methods[] = $reflectionMethod->getName();
        }
        foreach ($class->getProperties() as $property) {
            $attributes = $property->getAttributes($this->bindingAttributeClass);
            foreach ($attributes as $attribute) {
                $listener = $attribute->newInstance();
                if (get_class($listener) === $this->bindingAttributeClass) {
                    $this->addBinding(
                        $collection,
                        $listener,
                        $methods,
                        $property
                    );
                }
            }
        }
        return $collection;
    }

    /**
     * Add binding class to BindingCollection
     *
     * @param BindingCollection $collection
     * @param \SOW\BindingBundle\Attribute\Binding $attribute
     * @param array $methods
     * @param ReflectionProperty $property
     *
     * @return void
     */
    protected function addBinding(
        BindingCollection $collection,
        \SOW\BindingBundle\Attribute\Binding $attribute,
        array $methods,
        ReflectionProperty $property
    ) {
        $propertyName = $property->getName();
        $setter = $attribute->getSetter() ?? 'set' . ucfirst($propertyName);
        $getter = $attribute->getGetter() ?? 'get' . ucfirst($propertyName);
        if (in_array($setter, $methods)) {
            $subCollection = null;
            if (self::isNotScalar($attribute->getType())) {
                $subLoader = new AttributeClassLoader($this->em, $this->bindingAttributeClass);
                $subCollection = $subLoader->load($attribute->getType());
            }
            $binding = new Binding(
                $attribute->getKey() ?? $propertyName,
                $setter,
                $attribute->getType(),
                $attribute->getMin(),
                $attribute->getMax(),
                $subCollection,
                $getter,
                $attribute->isNullable()
            );
            $collection->addBinding($binding);
        }
    }

    /**
     * Check if resource is supported
     *
     * @param mixed $resource
     * @param null $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource)
            && preg_match(
                '/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/',
                $resource
            )
            && (!$type || 'attribute' === $type);
    }

    /**
     * Not implemented
     *
     * @return LoaderResolverInterface|void
     */
    public function getResolver()
    {
        return;
    }

    /**
     * Not implemented
     *
     * @param LoaderResolverInterface $resolver
     *
     * @return void
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        return;
    }

    /**
     * isNotScalar
     *
     * @param string|null $type
     *
     * @return bool
     */
    public static function isNotScalar(?string $type = null): bool
    {
        return (!empty($type) && !in_array($type, self::SCALAR_TYPES));
    }

    /**
     * isNotScalar
     *
     * @param string|null $type
     *
     * @return bool
     */
    public static function isScalar(?string $type = null): bool
    {
        return !self::isNotScalar($type);
    }
}
