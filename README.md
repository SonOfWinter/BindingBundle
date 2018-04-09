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
```

Use Binder service for bind an array to entity

```php
    public function __construct(Binder $binder)
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

