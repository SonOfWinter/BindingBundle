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
use Doctrine\Persistence\Proxy;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use SOW\BindingBundle\Utils\TypeUtils;
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

    protected string $bindingAttributeClass;

    private EntityManagerInterface $em;

    private LoaderResolverInterface $resolver;

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
    public function setBindingAttributeClass($class): void
    {
        $this->bindingAttributeClass = $class;
    }

    /**
     * Load Binding data from class
     *
     * @param mixed $resource
     * @param null $type
     *
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @return BindingCollection
     */
    public function load(mixed $resource, $type = null): mixed
    {
        if (!class_exists($resource)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Class "%s" does not exist.',
                    $resource
                )
            );
        }
        if (str_contains($resource, Proxy::MARKER)) {
            $resource = $this->em->getClassMetadata($resource)->rootEntityName;
        }
        $resource = new ReflectionClass($resource);
        if ($resource->isAbstract()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Annotations from class "%s" cannot be read as it is abstract.',
                    $resource->getName()
                )
            );
        }
        $collection = new BindingCollection();
        $collection->addResource(new FileResource($resource->getFileName()));
        $methods = [];
        foreach ($resource->getMethods() as $reflectionMethod) {
            $methods[] = $reflectionMethod->getName();
        }
        foreach ($resource->getProperties() as $property) {
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
    ): void {
        $propertyName = $property->getName();
        $setter = $attribute->getSetter() ?? 'set' . ucfirst($propertyName);
        $getter = $attribute->getGetter() ?? 'get' . ucfirst($propertyName);
        if (in_array($setter, $methods)) {
            $subCollection = null;
            if (TypeUtils::isNotScalar($attribute->getType())) {
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
    public function supports(mixed $resource, $type = null): bool
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
     * @return LoaderResolverInterface
     */
    public function getResolver(): LoaderResolverInterface
    {
        return $this->resolver;
    }

    /**
     * Not implemented
     *
     * @param LoaderResolverInterface $resolver
     *
     * @return void
     */
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }
}
