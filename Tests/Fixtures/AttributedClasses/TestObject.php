<?php

namespace Fixtures\AttributedClasses;

use SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeSubObject;
use SOW\BindingBundle\Attribute\Binding;

class TestObject
{
    #[Binding(key: "firstname")]
    private string $firstname;

    #[Binding(key: "lastname", setter: "setOtherName")]
    private string $lastname;

    #[Binding(key: "userEmail")]
    private string $userEmail;

    private mixed $notBindProperty;

    #[Binding(key: "subObject", type: "SOW\BindingBundle\Tests\Fixtures\AttributedClasses\TestAttributeSubObject")]
    private TestAttributeSubObject $subObject;

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setOtherName(string $othername): self
    {
        $this->lastname = $othername;
        return $this;
    }

    public function getNotBindProperty(): string
    {
        return $this->notBindProperty;
    }

    public function setNotBindProperty(mixed $notBindProperty): self
    {
        $this->notBindProperty = $notBindProperty;
        return $this;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    public function getSubObject(): ?TestAttributeSubObject
    {
        return $this->subObject;
    }

    public function setSubObject(?TestAttributeSubObject $subObject): self
    {
        $this->subObject = $subObject;
        return $this;
    }
}
