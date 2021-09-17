<?php
/**
 * Binding annotation class
 *
 * @package  SOW\BindingBundle\Annotation
 * @author   Thomas LEDUC <thomaslmoi15@hotmail.fr>
 * @link     https://github.com/SonOfWinter/BindingBundle
 */

namespace SOW\BindingBundle\Annotation;

/**
 * Class Binding
 *
 * @package SOW\BindingBundle\Annotation
 * @Annotation
 * @Target("PROPERTY")
 */
class Binding
{
    /**
     * Entity property key
     *
     * @var string
     */
    public $key;

    /**
     * Entity setter method name
     *
     * @var string|null
     */
    public $setter;

    /**
     * Entity getter method name
     *
     * @var string|null
     */
    public $getter;

    /**
     * Entity property type
     *
     * @var string|null
     */
    public $type;

    /**
     * Min value length
     *
     * @var mixed
     */
    public $min;

    /**
     * Max value length
     *
     * @var mixed
     */
    public $max;

    /**
     * If value can be null
     *
     * @var bool
     */
    public $nullable = false;

    /**
     * Binding constructor.
     *
     * @param array $data
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $noUnderscoreKey = str_replace('_', '', $key);
            $method = 'set' . $noUnderscoreKey;
            if (!method_exists($this, $method)) {
                $message = sprintf(
                    'Unknown property "%s" on annotation "%s".',
                    $key,
                    get_class($this)
                );
                throw new \BadMethodCallException($message);
            }
            $this->$method($value);
        }
    }

    /**
     * Getter for key
     *
     * @return mixed
     */
    public function getKey()
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
     * @return mixed
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * Setter for setter
     *
     * @param mixed $setter
     *
     * @return self
     */
    public function setSetter($setter): self
    {
        $this->setter = $setter;
        return $this;
    }

    /**
     * Getter for type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter for type
     *
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type): self
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
}
