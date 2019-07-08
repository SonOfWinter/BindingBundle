<?php

namespace SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\BindingBundle\Annotation as Binding;

/**
 * Class TestSubObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses
 */
class TestSubObject extends AbstractClass
{
    /**
     * @var string
     * @Binding\Binding(key="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Binding\Binding(key="lastname")
     */
    private $lastname;

    /**
     * @var TestSubSubObject
     * @Binding\Binding(type="SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestSubSubObject")
     */
    private $subSubObject;

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
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
     * Getter for testSubSubObject
     *
     * @return TestSubSubObject
     */
    public function getSubSubObject(): ?TestSubSubObject
    {
        return $this->subSubObject;
    }

    /**
     * Setter for testSubSubObject
     *
     * @param TestSubSubObject $subSubObject
     *
     * @return self
     */
    public function setSubSubObject(?TestSubSubObject $subSubObject): self
    {
        $this->subSubObject = $subSubObject;
        return $this;
    }
}
