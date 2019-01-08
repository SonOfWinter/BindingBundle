Installation
============


Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/binding-bundle
```

Usage
=====

Define binding properties in your entity

For v0.3.0 and above

```php
    /**
     * @var string
     * @Binding(key="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Binding(key="lastname", setter="setOtherName")
     */
    private $lastname;

    /**
     * @var string
     * @Binding(key="age", type="integer")
     */
    private $age;

    /**
     * @var string
     * @Binding()
     */
    private $userEmail;
```

For v0.2.0 and below

```php
    /**
     * @var string
     * @Binding(name="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Binding(name="lastname", setter="setOtherName")
     */
    private $lastname;

    /**
     * @var string
     * @Binding(name="age", type="integer")
     */
    private $age;

    /** 
     * @var string
     * @Binding(name="userEmail")
     */
    private $userEmail;  
```

You must defined the key|name property. It's the array value's key.

The setter property is used if you want to use another setter.

The type property is used if you want to make a type check.
A BinderTypeException is throws if the type doens't correspond.

Use Binder service for bind an array to entity

```php
    public function __construct(BinderInterface $binder)
    {
        $this->binder = $binder;
    }

    function bind(BindableEntity $be, array $data): BindableEntity
    {
        // $data = ['lastname' => 'Doe', 'firstname' => 'John', 'age' => 20, 'userEmail' => 'some.email@mail.com'];
        $this->binder->bind($be, $data);
        return $be;
    }
```

New in v0.4 inclusion and exclusion

```php
    public function bind(&$object, array $params = [], array $include = [], array $exclude = [])
```

$include is a key array required in $params, if one or more keys are missing, an exception is thrown

$exclude is a key array ignored in $params. No exception was thrown is a key is present.

Next / Ideas
============

> Get array of all property or all keys from entity

> recursive (bind children)

> Min and Max value (number only)
