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
     * Binding constructor.
     * @param string $key
     * @param string $setter
     */
    public function __construct(string $key, string $setter, $type = '')
    {
        $this->key = $key;
        $this->setter = $setter;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'key'    => $this->key,
            'setter' => $this->setter,
            'type'   => $this->type
        ]);
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
     * @return string
     */
    public function getType(): string
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
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
}
