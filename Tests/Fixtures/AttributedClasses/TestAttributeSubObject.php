<?php

namespace SOW\BindingBundle\Tests\Fixtures\AttributedClasses;

use SOW\BindingBundle\Attribute\Binding;

/**
 * Class TestAttributeSubObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AttributedClasses
 */
class TestAttributeSubObject extends AbstractClass
{
    #[Binding(key: "firstname")]
    private string $firstname = '';

    #[Binding(key: "lastname")]
    private string $lastname = '';

    #[Binding(key: "subSubObject", type: "SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestSubSubObject")]
    private TestAttributeSubSubObject $subSubObject;

    /**
     * TestAttributeSubObject constructor.
     */
    public function __construct()
    {
        $this->subSubObject = new TestAttributeSubSubObject();
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
     * Getter for subSubObject
     *
     * @return TestAttributeSubSubObject
     */
    public function getSubSubObject(): TestAttributeSubSubObject
    {
        return $this->subSubObject;
    }

    /**
     * Setter for subSubObject
     *
     * @param TestAttributeSubSubObject $subSubObject
     *
     * @return self
     */
    public function setSubSubObject(TestAttributeSubSubObject $subSubObject): self
    {
        $this->subSubObject = $subSubObject;
        return $this;
    }
}
