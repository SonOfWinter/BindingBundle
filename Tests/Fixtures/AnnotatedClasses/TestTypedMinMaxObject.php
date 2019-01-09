<?php

namespace SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\BindingBundle\Annotation as Binding;

/**
 * Class TestTypedMinMaxObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses
 */
class TestTypedMinMaxObject extends AbstractClass
{
    /**
     * @var string
     * @Binding\Binding(key="firstname", type="string", min=2, max=20)
     */
    private $firstname;

    /**
     * @var string
     * @Binding\Binding(key="lastname", setter="setOtherName", type="string", min=2, max=255)
     */
    private $lastname;

    /**
     * @var integer
     * @Binding\Binding(key="age", type="integer", min=0, max=100)
     */
    private $age;

    /**
     * @var array
     * @Binding\Binding(type="array", min=1, max=3)
     */
    private $letterList;

    /**
     * @var mixed
     */
    private $notBindProperty;

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
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $othername
     */
    public function setOtherName(string $othername)
    {
        $this->lastname = $othername;
    }

    /**
     * @return string
     */
    public function getNotBindProperty()
    {
        return $this->notBindProperty;
    }

    /**
     * @param string $notBindProperty
     */
    public function setNotBindProperty($notBindProperty): void
    {
        $this->notBindProperty = $notBindProperty;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
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
}
