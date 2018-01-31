<?php

namespace SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses;

use SOW\BindingBundle\Annotation as Binding;

/**
 * Class Fake
 * @package SOW\BindingBundle\Tests\Service
 * @codeCoverageIgnore
 */
class TestObject extends AbstractClass
{
    /**
     * @var string
     * @Binding\Binding(name="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Binding\Binding(name="lastname", setter="setOtherName")
     */
    private $lastname;

    /**
     * @var mixed
     */
    private $notBindProperty;

    /**
     * @return string
     */
    public function getFirstname(): string
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
    public function getLastname(): string
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
}