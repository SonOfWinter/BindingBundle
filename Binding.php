<?php
/**
 * Binding class
 *
 * @package  SOW\BindingBundle
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle;

/**
 * Class Binding
 *
 * @package SOW\BindingBundle
 */
class Binding
{
    private string $key;

    private string $setter;

    private string $getter;

    private ?string $type;

    private ?int $min;

    private ?int $max;

    private ?BindingCollection $subCollection;

    private ?bool $nullable;

    /**
     * Binding constructor.
     *
     * @param string $key
     * @param string $setter
     * @param string|null $type
     * @param int|null $min
     * @param int|null $max
     * @param BindingCollection|null $subCollection
     * @param string|null $getter
     * @param bool|null $nullable
     */
    public function __construct(
        string $key = '',
        string $setter = '',
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

    public function __serialize(): array
    {
        return [
            'key' => $this->key,
            'setter' => $this->setter,
            'getter' => $this->getter,
            'type' => $this->type,
            'min' => $this->min,
            'max' => $this->max,
            'nullable' => $this->nullable,
        ];
    }

    public function __unserialize(array $data): void
    {
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
     * @return string|null
     */
    public function getGetter(): ?string
    {
        return $this->getter;
    }

    /**
     * Setter for getter
     *
     * @param string|null $getter
     *
     * @return self
     */
    public function setGetter(?string $getter): self
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
