<?php

namespace SOW\BindingBundle\Tests\Fixtures\AttributedClasses;

use SOW\BindingBundle\Attribute\Binding;

/**
 * Class TestAttributeTypedMinMaxObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AttributedClasses
 */
class TestAttributeTypedMinMaxObject extends AbstractClass
{
    #[Binding(key: "firstname", type: "string", min: 2, max: 20)]
    private string $firstname = '';

    #[Binding(key: "lastname", setter: "setOtherName", type: "string", min: 2, max: 255)]
    private string $lastname = '';

    #[Binding(key: "age", type: "integer", min: 0, max: 100)]
    private int $age = 0;

    #[Binding(key: "letterList", type: "array", min: 1, max: 3)]
    private array $letterList = [];

    private mixed $notBindProperty = null;


    /**
     * @param string $othername
     */
    public function setOtherName(string $othername)
    {
        $this->lastname = $othername;
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
     * Getter for age
     *
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * Setter for age
     *
     * @param int $age
     *
     * @return self
     */
    public function setAge(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    /**
     * Getter for letterList
     *
     * @return array
     */
    public function getLetterList(): array
    {
        return $this->letterList;
    }

    /**
     * Setter for letterList
     *
     * @param array $letterList
     *
     * @return self
     */
    public function setLetterList(array $letterList): self
    {
        $this->letterList = $letterList;
        return $this;
    }

    /**
     * Getter for notBindProperty
     *
     * @return mixed
     */
    public function getNotBindProperty(): mixed
    {
        return $this->notBindProperty;
    }

    /**
     * Setter for notBindProperty
     *
     * @param mixed $notBindProperty
     *
     * @return self
     */
    public function setNotBindProperty(mixed $notBindProperty): self
    {
        $this->notBindProperty = $notBindProperty;
        return $this;
    }
}
