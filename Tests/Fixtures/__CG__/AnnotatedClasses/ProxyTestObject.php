<?php

namespace SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses;


use Closure;
use Doctrine\Persistence\Proxy;
use SOW\BindingBundle\Attribute\Binding;

/**
 * Class TestObject
 *
 * @package SOW\BindingBundle\Tests\Fixtures\__CG__\AnnotatedClasses
 */
class ProxyTestObject implements Proxy
{
     #[Binding(key: "firstname")]
    private string $firstname;

    /**
     */
     #[Binding(key: "lastname", setter: "setOtherName")]
    private string $lastname;

    /**
     * @Binding\Binding()
     */
    #[Binding(key: "userEmail")]
    private string $userEmail;

    /**
     * @var mixed
     */
    private mixed $notBindProperty;

    #[Binding(key: "subObject", type: "SOW\BindingBundle\Tests\Fixtures\AnnotatedClasses\TestSubObject")]
    private TestSubObject $subObject;

    private bool $init = false;

    /**
     * @return string
     */
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


    public function getSubObject(): ?TestSubObject
    {
        return $this->subObject;
    }

    public function setSubObject(?TestSubObject $subObject): self
    {
        $this->subObject = $subObject;
        return $this;
    }

    /**
     * Initializes this proxy if its not yet initialized.
     *
     * Acts as a no-op if already initialized.
     */
    public function __load(): void
    {
        $this->init = true;
    }

    /**
     * Returns whether this proxy is initialized or not.
     *
     * @return bool
     */
    public function __isInitialized(): bool
    {
        return $this->init;
    }

    public function __setInitialized($initialized)
    {
        // TODO: Implement __setInitialized() method.
    }

    public function __setInitializer(?Closure $initializer = null)
    {
        // TODO: Implement __setInitializer() method.
    }

    public function __getInitializer()
    {
        // TODO: Implement __getInitializer() method.
    }

    public function __setCloner(?Closure $cloner = null)
    {
        // TODO: Implement __setCloner() method.
    }

    public function __getCloner()
    {
        // TODO: Implement __getCloner() method.
    }

    public function __getLazyProperties()
    {
        // TODO: Implement __getLazyProperties() method.
    }
}
