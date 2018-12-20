<?php

namespace SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses;

use SOW\BindingBundle\Annotation as Binding;
use SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\AbstractClass;

/**
 * Class TestObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses
 */
class ProxyTestObject extends AbstractClass
{
    /**
     * @var string
     * @Binding\Binding(key="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Binding\Binding(key="lastname", setter="setOtherName")
     */
    private $lastname;

    /**
     * @var string
     * @Binding\Binding()
     */
    private $userEmail;

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
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
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
    public function setOtherName(string $othername): self
    {
        $this->lastname = $othername;
        return $this;
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
    public function setNotBindProperty($notBindProperty): self
    {
        $this->notBindProperty = $notBindProperty;
        return $this;
    }

    /**
     * Getter for userEmail
     *
     * @return string
     */
    public function getUserEmail()
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
}
