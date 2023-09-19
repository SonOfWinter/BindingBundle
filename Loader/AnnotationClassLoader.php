<?php
/**
 * Annotation class loader
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
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Config\Resource\FileResource;

/**
 * Class AnnotationClassLoader
 *
 * @package SOW\BindingBundle\Loader
 */
class AnnotationClassLoader implements LoaderInterface
{
    public const SCALAR_TYPES = ['integer', 'float', 'string', 'boolean', 'array'];

    /**
     * Reader for annotation
     */
    protected Reader $reader;

    /**
     * Annotation class name
     */
    protected string $bindingAnnotationClass;

    private EntityManagerInterface $em;

    /**
     * AnnotationClassLoader constructor.
     *
     * @param Reader $reader
     * @param EntityManagerInterface $em
     * @param string $bindingAnnotationClass
     */
    public function __construct(Reader $reader, EntityManagerInterface $em, string $bindingAnnotationClass)
    {
        $this->reader = $reader;
        $this->em = $em;
        $this->bindingAnnotationClass = $bindingAnnotationClass;
    }

    /**
     * Sets the annotation class to read binding properties from.
     *
     * @param string $class
     *
     * @return void
     */
    public function setBindingAnnotationClass(string $class): void
    {
        $this->bindingAnnotationClass = $class;
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
            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof $this->bindingAnnotationClass) {
                    $this->addBinding(
                        $collection,
                        $annot,
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
     * @param \SOW\BindingBundle\Annotation\Binding $annot
     * @param array $methods
     * @param ReflectionProperty $property
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @return void
     */
    protected function addBinding(
        BindingCollection $collection,
        \SOW\BindingBundle\Annotation\Binding $annot,
        array $methods,
        ReflectionProperty $property
    ): void {
        $propertyName = $property->getName();
        $setter = $annot->getSetter() ?? 'set' . ucfirst($propertyName);
        $getter = $annot->getGetter() ?? 'get' . ucfirst($propertyName);
        if (in_array($setter, $methods)) {
            $subCollection = null;
            if (self::isNotScalar($annot->getType())) {
                $subLoader = new AnnotationClassLoader($this->reader, $this->em, $this->bindingAnnotationClass);
                $subCollection = $subLoader->load($annot->getType());
            }
            $binding = new Binding(
                $annot->getKey() ?? $propertyName,
                $setter,
                $annot->getType(),
                $annot->getMin(),
                $annot->getMax(),
                $subCollection,
                $getter,
                $annot->isNullable()
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
    public function supports($resource, $type = null): bool
    {
        return is_string($resource)
            && preg_match(
                '/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/',
                $resource
            )
            && (!$type || 'annotation' === $type);
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
    public function setResolver(LoaderResolverInterface $resolver): void
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
