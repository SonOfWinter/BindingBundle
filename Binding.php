<?php

namespace SOW\BindingBundle;

/**
 * Class Binding
 * @package SOW\BindingBundle
 */
class Binding implements \Serializable
{
    /** @var string */
    private $key = '';

    /** @var string */
    private $setter = '';

    /** @var string */
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
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Binding
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * @param string $setter
     * @return Binding
     */
    public function setSetter(string $setter): self
    {
        $this->setter = $setter;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function __toString()
    {
        return $this->getKey();
    }
}