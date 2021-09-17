<?php

namespace SOW\BindingBundle\Tests\Fixtures\AttributedClasses;

use SOW\BindingBundle\Attribute\Binding;

/**
 * Class TestAttributeObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AttributedClasses
 */
class TestAttributeObject extends AbstractClass
{
    #[Binding(key: "firstname")]
    private string $firstname = '';

    #[Binding(key: "lastname", setter: "setOtherName")]
    private string $lastname = '';

    #[Binding(key: "userEmail")]
    private string $userEmail = '';

    private mixed $notBindProperty = null;

    #[Binding(key: "subObject", type: "SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeSubObject")]
    private TestAttributeSubObject $subObject;

    /**
     * TestAttributeObject constructor.
     */
    public function __construct()
    {
        $this->subObject = new TestAttributeSubObject();
    }

    /**
     * Getter for firstname
     *
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Setter for firstname
     *
     * @param string $firstname
     *
     * @return self
     */
    public function setFirstname(string $firstname): self
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

    /**
     * Getter for userEmail
     *
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * Setter for userEmail
     *
     * @param string $userEmail
     *
     * @return self
     */
    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    /**
     * Getter for notBindProperty
     *
     * @return mixed|null
     */
    public function getNotBindProperty(): mixed
    {
        return $this->notBindProperty;
    }

    /**
     * Setter for notBindProperty
     *
     * @param mixed|null $notBindProperty
     *
     * @return self
     */
    public function setNotBindProperty(mixed $notBindProperty): self
    {
        $this->notBindProperty = $notBindProperty;
        return $this;
    }

    /**
     * Getter for subObject
     *
     * @return TestAttributeSubObject
     */
    public function getSubObject(): TestAttributeSubObject
    {
        return $this->subObject;
    }

    /**
     * Setter for subObject
     *
     * @param TestAttributeSubObject $subObject
     *
     * @return self
     */
    public function setSubObject(TestAttributeSubObject $subObject): self
    {
        $this->subObject = $subObject;
        return $this;
    }

    /**
     * @param string $othername
     */
    public function setOtherName(string $othername): self
    {
        $this->lastname = $othername;
        return $this;
    }
}
