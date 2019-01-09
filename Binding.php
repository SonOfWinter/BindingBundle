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
    private $type = '';

    /**
     * @var mixed
     */
    private $min = null;

    /**
     * @var mixed
     */
    private $max = null;

    /**
     * Binding constructor.
     *
     * @param string $key
     * @param string $setter
     * @param string $type
     * @param null $min
     * @param null $max
     */
    public function __construct(string $key, string $setter, $type = '', $min = null, $max = null)
    {
        $this->key = $key;
        $this->setter = $setter;
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(
            [
                'key' => $this->key,
                'setter' => $this->setter,
                'type' => $this->type,
                'min' => $this->min,
                'max' => $this->max
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
        $this->type = $data['type'];
        $this->min = $data['min'];
        $this->max = $data['max'];
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
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
}
