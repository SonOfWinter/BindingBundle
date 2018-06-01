Installation
============


Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/binding-bundle
```

Usage
=====

Define binding properties in your entity


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
     * @Binding(name="lastname", type="integer")
     */
    private $age;
```

You must defined the name property. It's the array value'key
the setter property is used if you want to use another setter
the type property is used if you want to make a type check. A BinderTypeException is throws if the type doens't correspond

Use Binder service for bind an array to entity

```php
    public function __construct(BinderInterface $binder)
    {
        $this->binder = $binder;
    }

    function bind(BindaleEntity $be, array $data): BindableEntity
    {
        // $data = ['lastname' => 'Doe', 'firstname' => 'John' ];
        $this->binder->bind($be, $data);
        return $be;
    }
```

