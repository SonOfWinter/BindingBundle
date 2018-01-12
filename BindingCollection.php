<?php

namespace SOW\BindingBundle;

use Symfony\Component\Config\Resource\ResourceInterface;
use Traversable;

class BindingCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Binding[]
     */
    private $bindings = [];

    /**
     * @var array
     */
    private $resources = array();


    public function getIterator()
    {
        return new \ArrayIterator($this->bindings);
    }

    public function count()
    {
        return count($this->bindings);
    }

    public function add(Binding $binding)
    {
        unset($this->bindings[$binding->getKey()]);
        $this->bindings[$binding->getKey()] = $binding;
    }

    public function all()
    {
        return $this->bindings;
    }

    public function get($key)
    {
        return isset($this->bindings[$key]) ? $this->bindings[$key] : null;
    }

    /**
     * @param string|string[] $key The binding key or an array of binding keys
     */
    public function remove($key)
    {
        foreach ((array)$key as $k) {
            unset($this->bindings[$k]);
        }
    }
    public function addCollection(BindingCollection $collection)
    {
        foreach ($collection->all() as $key => $binding) {
            unset($this->bindings[$key]);
            $this->bindings[$key] = $binding;
        }

        foreach ($collection->getResources() as $resource) {
            $this->addResource($resource);
        }
    }


    /**
     * Returns an array of resources loaded to build this collection.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources()
    {
        return array_values($this->resources);
    }
    /**
     * Adds a resource for this collection. If the resource already exists
     * it is not added.
     */
    public function addResource(ResourceInterface $resource)
    {
        $key = (string) $resource;
        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }
}