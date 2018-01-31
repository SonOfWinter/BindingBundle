<?php

namespace SOW\BindingBundle\Loader;

use SOW\BindingBundle\Binding;
use SOW\BindingBundle\BindingCollection;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Config\Resource\FileResource;

class AnnotationClassLoader implements LoaderInterface
{
    protected $reader;

    /** @var string */
    protected $bindingAnnotationClass = 'SOW\\BindingBundle\\Annotation\\Binding';

    /**
     * AnnotationClassLoader constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Sets the annotation class to read binding properties from.
     *
     * @param string $class A fully-qualified class name
     */
    public function setBindingAnnotationClass($class)
    {
        $this->bindingAnnotationClass = $class;
    }

    /**
     * @param mixed $class
     * @param null  $type
     * @return BindingCollection
     * @throws \InvalidArgumentException
     */
    public function load($class, $type = null)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }
        $class = new \ReflectionClass($class);
        if ($class->isAbstract()) {
            throw new \InvalidArgumentException(sprintf('Annotations from class "%s" cannot be read as it is abstract.',
                $class->getName()));
        }
        $collection = new BindingCollection();
        $collection->addResource(new FileResource($class->getFileName()));
        foreach ($class->getMethods() as $reflectionMethod) {
            $methods[] = $reflectionMethod->getName();
        }
        foreach ($class->getProperties() as $property) {

            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof $this->bindingAnnotationClass) {
                    $this->addBinding($collection, $annot, $methods, $class, $property);
                }
            }
        }
        return $collection;
    }

    /**
     * @param BindingCollection                     $collection
     * @param \SOW\BindingBundle\Annotation\Binding $annot
     * @param array                                 $methods
     * @param \ReflectionClass                      $class
     * @param \ReflectionProperty                   $property
     */
    protected function addBinding(BindingCollection $collection,
                                  \SOW\BindingBundle\Annotation\Binding $annot,
                                  array $methods,
                                  \ReflectionClass $class,
                                  \ReflectionProperty $property)
    {
        $propertyName = $property->getName();
        $method = $annot->getSetter() ?: 'set' . ucfirst($propertyName);
        if (in_array($method, $methods)) {
            $binding = new Binding($annot->getName(), $method);
            $collection->add($binding);
        }
    }

    /**
     * @param mixed $resource
     * @param null  $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/',
                $resource) && (!$type || 'annotation' === $type);
    }

    public function getResolver(){}

    public function setResolver(LoaderResolverInterface $resolver){}
}