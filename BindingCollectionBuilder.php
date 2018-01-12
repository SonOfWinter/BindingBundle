<?php

namespace SOW\BindingBundle;

use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\ResourceInterface;

class BindingCollectionBuilder
{
    private $loader;

    private $bindings = [];

    private $resources = [];

    /**
     * BindingCollectionBuilder constructor.
     * @param LoaderInterface|null $loader
     */
    public function __construct(LoaderInterface $loader = null)
    {
        $this->loader = $loader;
    }

    /**
     * @return BindingCollectionBuilder
     */
    public function createBuilder()
    {
        return new self($this->loader);
    }

    /**
     * @param Binding $binding
     * @param null    $name
     * @return $this
     */
    public function addRoute(Binding $binding, $name = null)
    {
        if (null === $name) {
            // used as a flag to know which routes will need a name later
            $name = $binding->getKey();
        }
        $this->bindings[$name] = $binding;
        return $this;
    }

    /**
     * @param ResourceInterface $resource
     * @return BindingCollectionBuilder
     */
    private function addResource(ResourceInterface $resource): BindingCollectionBuilder
    {
        $this->resources[] = $resource;
        return $this;
    }

    /**
     * @param      $resource
     * @param null $type
     * @return BindingCollectionBuilder
     * @throws FileLoaderLoadException
     * @throws \BadMethodCallException
     * @throws \Exception
     */
    public function import($resource, $type = null)
    {
        /** @var BindingCollection[] $collections */
        $collections = $this->load($resource, $type);
        // create a builder from the RouteCollection
        $builder = $this->createBuilder();
        foreach ($collections as $collection) {
            if (null === $collection) {
                continue;
            }
            foreach ($collection->all() as $name => $route) {
                $builder->addRoute($route, $name);
            }
            foreach ($collection->getResources() as $resource) {
                $builder->addResource($resource);
            }
            // mount into this builder
            $this->mount($builder);
        }
        return $builder;
    }

    /**
     * @param BindingCollectionBuilder $builder
     */
    public function mount(BindingCollectionBuilder $builder)
    {
        $this->bindings[] = $builder;
    }

    /**
     * @return BindingCollection
     */
    public function build()
    {
        $bindingCollection = new BindingCollection();
        foreach ($this->bindings as $name => $binding) {
            if ($binding instanceof Binding) {
                $bindingCollection->add($binding);
            } else {
                /* @var self $binding */
                $subCollection = $binding->build();
                $bindingCollection->addCollection($subCollection);
            }
        }
        foreach ($this->resources as $resource) {
            $bindingCollection->addResource($resource);
        }
        return $bindingCollection;
    }

    /**
     * @param             $resource
     * @param string|null $type
     * @return BindingCollection[]
     * @throws FileLoaderLoadException
     * @throws \BadMethodCallException
     * @throws \Exception
     */
    private function load($resource, string $type = null): array
    {
        if (null === $this->loader) {
            throw new \BadMethodCallException('Cannot import other routing resources: you must pass a LoaderInterface when constructing RouteCollectionBuilder.');
        }
        if ($this->loader->supports($resource, $type)) {
            $collections = $this->loader->load($resource, $type);
            return is_array($collections) ? $collections : [$collections];
        }
        if (null === $resolver = $this->loader->getResolver()) {
            throw new FileLoaderLoadException($resource, null, null, null, $type);
        }
        if (false === $loader = $resolver->resolve($resource, $type)) {
            throw new FileLoaderLoadException($resource, null, null, null, $type);
        }
        $collections = $loader->load($resource, $type);
        return is_array($collections) ? $collections : [$collections];
    }
}