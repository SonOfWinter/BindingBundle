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
        // $data = ['lastname' => 'Doe', 'firstname' => 'John', 'age' => 20];
        $this->binder->bind($be, $data);
        return $be;
    }
```

