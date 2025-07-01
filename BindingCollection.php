<?php

/**
 * Binding Collection class
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Symfony\Component\Config\Resource\ResourceInterface;
use Traversable;

/**
 * Class BindingCollection
 *
 * @package SOW\BindingBundle
 */
class BindingCollection implements IteratorAggregate, Countable
{
    /**
     * @var Binding[]
     */
    private array $bindings = [];

    /**
     * @var ResourceInterface[]
     */
    private array $resources = [];

    /**
     * @return ArrayIterator|Traversable
     */
    public function getIterator(): Traversable | ArrayIterator
    {
        return new ArrayIterator($this->bindings);
    }

    /**
     * Return number of binding element
     *
     * @return int
     */
    public function count(): int
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
    public function addBinding(Binding $binding): void
    {
        if (array_key_exists($binding->getKey(), $this->bindings)) {
            unset($this->bindings[$binding->getKey()]);
        }
        $this->bindings[$binding->getKey()] = $binding;
    }

    /**
     * Get all binding elements
     *
     * @return Binding[]
     */
    public function all(): array
    {
        return $this->bindings;
    }

    /**
     * Get a binding element by key
     *
     * @param string $key
     *
     * @return null|Binding
     */
    public function get(string $key): ?Binding
    {
        return $this->bindings[$key] ?? null;
    }

    /**
     * Remove a binding element by key
     *
     * @param string|string[] $key The binding key or an array of binding keys
     *
     * @return void
     */
    public function remove(string|array $key): void
    {
        if (!empty($key)) {
            foreach ((array)$key as $k) {
                unset($this->bindings[$k]);
            }
        }
    }

    /**
     * Merge the collection with a new collection
     *
     * @param BindingCollection $collection
     *
     * @return void
     */
    public function mergeCollection(BindingCollection $collection): void
    {
        foreach ($collection->all() as $key => $binding) {
            if (array_key_exists($key, $this->bindings)) {
                unset($this->bindings[$key]);
            }
            $this->bindings[$key] = $binding;
        }
        foreach ($collection->getResources() as $resource) {
            $this->addResource($resource);
        }
    }

    /**
     * Returns an array of resources loaded to build this collection.
     *
     * @return ResourceInterface[]
     */
    public function getResources(): array
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
    public function addResource(ResourceInterface $resource): void
    {
        $key = (string) $resource;
        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }
}
