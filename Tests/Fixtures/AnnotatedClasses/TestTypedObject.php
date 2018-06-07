<?php

namespace SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\BindingBundle\Annotation as Binding;

/**
 * Class TestTypedObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses
 */
class TestTypedObject extends AbstractClass
{
    /**
     * @var string
     * @Binding\Binding(key="firstname", type="string")
     */
    private $firstname;

    /**
     * @var string
     * @Binding\Binding(key="lastname", setter="setOtherName", type="string")
     */
    private $lastname;

    /**
     * @var integer
     * @Binding\Binding(key="age", type="integer")
     */
    private $age;

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
}
