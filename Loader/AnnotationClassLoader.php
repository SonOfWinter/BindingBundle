<?php

/**
 * Annotation class loader
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle\Loader
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Loader;

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
    /**
     * Reader for annotation
     *
     * @var Reader
     */
    protected $reader;

    /**
     * Annotation class name
     *
     * @var string
     */
    protected $bindingAnnotationClass;

    /**
     * AnnotationClassLoader constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader, $bindingAnnotationClass)
    {
        $this->reader = $reader;
        $this->bindingAnnotationClass = $bindingAnnotationClass;
    }

    /**
     * Sets the annotation class to read binding properties from.
     *
     * @param $class
     *
     * @return void
     */
    public function setBindingAnnotationClass($class)
    {
        $this->bindingAnnotationClass = $class;
    }

    /**
     * Load Binding data from class
     *
     * @param mixed $class
     * @param null  $type
     *
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     *
     * @return BindingCollection
     */
    public function load($class, $type = null) : BindingCollection
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Class "%s" does not exist.',
                    $class
                )
            );
        }
        $class = new \ReflectionClass($class);
        if ($class->isAbstract()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Annotations from class "%s" cannot be read as it is abstract.',
                    $class->getName()
                )
            );
        }
        $collection = new BindingCollection();
        $collection->addResource(new FileResource($class->getFileName()));
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
     * @param BindingCollection                     $collection
     * @param \SOW\BindingBundle\Annotation\Binding $annot
     * @param array                                 $methods
     * @param \ReflectionProperty                   $property
     *
     * @return void
     */
    protected function addBinding(
        BindingCollection $collection,
        \SOW\BindingBundle\Annotation\Binding $annot,
        array $methods,
        \ReflectionProperty $property
    ) {
        $propertyName = $property->getName();
        $method = $annot->getSetter() ?? 'set' . ucfirst($propertyName);
        if (in_array(
            $method,
            $methods
        )
        ) {
            $binding = new Binding(
                $annot->getKey() ?? $propertyName,
                $method,
                $annot->getType(),
                $annot->getMin(),
                $annot->getMax()
            );
            $collection->add($binding);
        }
    }

    /**
     * Check if resource is supported
     *
     * @param mixed $resource
     * @param null  $type
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
    public function setResolver(LoaderResolverInterface $resolver)
    {
        return;
    }
}
