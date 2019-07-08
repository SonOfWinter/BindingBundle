<?php

namespace SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\BindingBundle\Annotation as Binding;

/**
 * Class TestSubSubObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses
 */
class TestSubSubObject extends AbstractClass
{
    /**
     * @var string
     * @Binding\Binding()
     */
    private $city = '';

    /**
     * @var string
     * @Binding\Binding()
     */
    private $country = '';

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Getter for lastname
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Setter for lastname
     *
     * @param string $country
     *
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }
}
