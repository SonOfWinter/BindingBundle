<?php

namespace SOW\BindingBundle\Tests\Fixtures\AttributedClasses;

use SOW\BindingBundle\Attribute\Binding;

/**
 * Class TestAttributeNullableObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AttributedClasses
 */
class TestAttributeNullableObject extends AbstractClass
{
    #[Binding(key: "firstname", type: "string", nullable: true)]
    private ?string $firstname = null;

    #[Binding(key: "lastname", setter: "setOtherName", type: "string", nullable: false)]
    private string $lastname = '';

    /**
     * setOtherName
     *
     * @param string $othername
     *
     * @return void
     */
    public function setOtherName(string $othername)
    {
        $this->lastname = $othername;
    }

    /**
     * Getter for firstname
     *
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Setter for firstname
     *
     * @param string|null $firstname
     *
     * @return self
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Getter for lastname
     *
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Setter for lastname
     *
     * @param string $lastname
     *
     * @return self
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }
}
