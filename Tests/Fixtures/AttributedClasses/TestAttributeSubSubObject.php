<?php

namespace SOW\BindingBundle\Tests\Fixtures\AttributedClasses;

use SOW\BindingBundle\Attribute\Binding;

/**
 * Class TestAttributeSubSubObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AttributedClasses
 */
class TestAttributeSubSubObject extends AbstractClass
{
    #[Binding(key: "city")]
    private string $city = '';

    #[Binding(key: "country")]
    private string $country = '';

    /**
     * Getter for city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Setter for city
     *
     * @param string $city
     *
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Getter for country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Setter for country
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
