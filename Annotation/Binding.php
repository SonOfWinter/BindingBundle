<?php

namespace SOW\BindingBundle\Annotation;


/**
 * Class Binding
 *
 * @Annotation
 *
 * @Target("PROPERTY")
 */
class Binding
{
    /** @var string */
    public $name;

    public $setter;

    public $type;

    /**
     * @param array $data An array of key/value parameters
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, get_class($this)));
            }
            $this->$method($value);
        }
    }

    /**
     * @param string $name
     * @return Binding
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $setter
     * @return Binding
     */
    public function setSetter($setter): self
    {
        $this->setter = $setter;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return self
     */
    public function setType($type): self
    {
        $this->type = $type;
        return $this;
    }

}