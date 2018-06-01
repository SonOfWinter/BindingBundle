<?php

/**
 * Binding class
 *
 * PHP Version 7.1
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Class BindingCollection
 *
 * @package SOW\BindingBundle
 */
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

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->bindings);
    }

    /**
     * Return number of binding element
     *
     * @return int
     */
    public function count()
    {
        return count($this->bindings);
    }

    /**
     * Add a binding element
     *
     * @param Binding $binding
     *
     * @return void
     */
    public function add(Binding $binding)
    {
        unset($this->bindings[$binding->getKey()]);
        $this->bindings[$binding->getKey()] = $binding;
    }

    /**
     * Get all binding elements
     *
     * @return Binding[]
     */
    public function all()
    {
        return $this->bindings;
    }

    /**
     * Get a binding element by key
     *
     * @param $key
     *
     * @return null|Binding
     */
    public function get($key)
    {
        return isset($this->bindings[$key]) ? $this->bindings[$key] : null;
    }

    /**
     * Remove a binding element by key
     *
     * @param string|string[] $key The binding key or an array of binding keys
     *
     * @return void
     */
    public function remove($key)
    {
        foreach ((array)$key as $k) {
            unset($this->bindings[$k]);
        }
    }

    /**
     * Merge the collection with a new collection
     *
     * @param BindingCollection $collection
     *
     * @return void
     */
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
     * @return array
     */
    public function getResources()
    {
        return array_values($this->resources);
    }

    /**
     * Adds a resource for this collection. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource
     *
     * @return void
     */
    public function addResource(ResourceInterface $resource)
    {
        $key = (string) $resource;
        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }
}
