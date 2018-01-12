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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $setter
     */
    public function setSetter($setter): void
    {
        $this->setter = $setter;
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
}