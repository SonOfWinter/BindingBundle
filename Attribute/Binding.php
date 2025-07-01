<?php

/**
 * Binding annotation class
 *
 * @package  SOW\BindingBundle\Attribute
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Attribute;

use Attribute;

/**
 * Class Binding
 *
 * @package SOW\BindingBundle\Attribute
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Binding
{
    public string $key;

    public string $setter;

    public string $getter;

    public ?string $type = null;

    public ?int $min = null;

    public ?int $max = null;

    public bool $nullable = false;

    /**
     * Binding constructor.
     *
     * @param string $key
     * @param string|null $setter
     * @param string|null $getter
     * @param string|null $type
     * @param int|null $min
     * @param int|null $max
     * @param bool $nullable
     */
    public function __construct(
        string $key,
        ?string $setter = null,
        ?string $getter = null,
        ?string $type = null,
        ?int $min = null,
        ?int $max = null,
        bool $nullable = false,
    ) {
        $this->key = $key;
        if ($setter) {
            $this->setter = $setter;
        } else {
            $this->setter = 'set' . ucwords($key);
        }
        if ($getter) {
            $this->getter = $getter;
        } else {
            $this->getter = 'get' . ucwords($key);
        }
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
        $this->nullable = $nullable;
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
     * Getter for type
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Setter for type
     *
     * @param string|null $type
     *
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Getter for min
     *
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * Setter for min
     *
     * @param int|null $min
     *
     * @return self
     */
    public function setMin(?int $min): self
    {
        $this->min = $min;
        return $this;
    }

    /**
     * Getter for max
     *
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * Setter for max
     *
     * @param int|null $max
     *
     * @return self
     */
    public function setMax(?int $max): self
    {
        $this->max = $max;
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
}
