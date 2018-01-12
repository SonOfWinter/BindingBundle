<?php

namespace SOW\BindingBundle;

class Binding implements \Serializable
{
    private $key = '';

    private $setter = '';

    /**
     * Binding constructor.
     * @param string $key
     * @param string $setter
     */
    public function __construct(string $key, string $setter)
    {
        $this->key = $key;
        $this->setter = $setter;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'key'    => $this->key,
            'setter' => $this->setter
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
     */
    public function setKey(string $key)
    {
        $this->key = $key;
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
     */
    public function setSetter(string $setter)
    {
        $this->setter = $setter;
    }

    public function __toString()
    {
        return $this->getKey();
    }
}