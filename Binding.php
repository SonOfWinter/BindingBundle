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

use phpDocumentor\Reflection\Types\Integer;

/**
 * Class Binding
 *
 * @package SOW\BindingBundle
 */
class Binding implements \Serializable
{
    /**
     * @var string
     */
    private $key = '';

    /**
     * @var string
     */
    private $setter = '';

    /**
     * @var string
     */
    private $getter = '';

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var integer
     */
    private $min = null;

    /**
     * @var integer
     */
    private $max = null;

    /**
     * @var BindingCollection
     */
    private $subCollection;

    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * Binding constructor.
     *
     * @param string $key
     * @param string $setter
     * @param string $type
     * @param int $min
     * @param int $max
     * @param BindingCollection $subCollection
     * @param string|null $getter
     * @param bool|null $nullable
     */
    public function __construct(
        string $key,
        string $setter,
        ?string $type = '',
        ?int $min = null,
        ?int $max = null,
        ?BindingCollection $subCollection = null,
        ?string $getter = '',
        ?bool $nullable = false
    ) {
        $this->key = $key;
        $this->setter = $setter;
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
        $this->subCollection = $subCollection;
        $this->getter = $getter;
        $this->nullable = $nullable;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        // TODO serialize subCollection
        return serialize(
            [
                'key' => $this->key,
                'setter' => $this->setter,
                'getter' => $this->getter,
                'type' => $this->type,
                'min' => $this->min,
                'max' => $this->max,
                'nullable' => $this->nullable
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->key = $data['key'];
        $this->setter = $data['setter'];
        $this->getter = $data['getter'];
        $this->type = $data['type'];
        $this->min = intval($data['min']);
        $this->max = intval($data['max']);
        $this->nullable = boolval($data['nullable']);
    }

    /**
     * Getter for key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Setter for key
     *
     * @param string $key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Getter for setter
     *
     * @return string
     */
    public function getSetter(): string
    {
        return $this->setter;
    }

    /**
     * Setter for setter
     *
     * @param string $setter
     *
     * @return self
     */
    public function setSetter(string $setter): self
    {
        $this->setter = $setter;
        return $this;
    }

    /**
     * Getter for type
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter for type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Getter for min
     *
     * @return mixed
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Setter for min
     *
     * @param mixed $min
     *
     * @return self
     */
    public function setMin($min): self
    {
        $this->min = $min;
        return $this;
    }

    /**
     * Getter for max
     *
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Setter for max
     *
     * @param mixed $max
     *
     * @return self
     */
    public function setMax($max): self
    {
        $this->max = $max;
        return $this;
    }

    /**
     * Getter for subCollection
     *
     * @return BindingCollection|null
     */
    public function getSubCollection(): ?BindingCollection
    {
        return $this->subCollection;
    }

    /**
     * Setter for subCollection
     *
     * @param BindingCollection|null $subCollection
     *
     * @return self
     */
    public function setSubCollection(?BindingCollection $subCollection): self
    {
        $this->subCollection = $subCollection;
        return $this;
    }

    /**
     * Getter for getter
     *
     * @return string
     */
    public function getGetter(): string
    {
        return $this->getter;
    }

    /**
     * Setter for getter
     *
     * @param string $getter
     *
     * @return self
     */
    public function setGetter(string $getter): self
    {
        $this->getter = $getter;
        return $this;
    }

    /**
     * Getter for nullable
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * Setter for nullable
     *
     * @param bool $nullable
     *
     * @return self
     */
    public function setNullable(bool $nullable): self
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
}
