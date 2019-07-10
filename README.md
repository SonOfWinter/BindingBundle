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
--------------------

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
     * @var integer
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
--------------------

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
     * @var integer
     * @Binding(name="age", type="integer")
     */
    private $age;

    /** 
     * @var string
     * @Binding(name="userEmail")
     */
    private $userEmail;
```

---

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
-----------------------------------

```php
    public function bind(&$object, array $params = [], array $include = [], array $exclude = [])
```

$include is a key array required in $params, if one or more keys are missing, an exception is thrown

$exclude is a key array ignored in $params. No exception was thrown is a key is present.

new in v0.5 min and max
-----------------------

```php
    /**
     * @var integer
     * @Binding(key="age", type="integer", min=0, max=100)
     */
    private $age;
```

The min and max value check if the value is in range defined by the two properties.

If not, a specific exception was thrown

Works with number (int/float), string (length) and array (count)

new in v0.6 child binder
------------------------

```php
    /** 
     * @var Test
     * @Binding(type="App\Entity\Test")
     */
    private $test;
```

A child entity can be binding when the type is set with the entity namespace.

The getter is use to get the sub entity.
If the sub entity is null, it try to create him (without parameter), if fail the binder skip sub entity.
So if the constructor need parameters, the sub entity must be defined before the binder action. 

Exemple of data :
 
```php
$data = [
    'lastname' => 'Doe', 
    'firstname' => 'John', 
    'age' => 20, 
    'userEmail' => 'some.email@mail.com',
    'test' => [
        'testProps1' => 'value',
        'testProps2' => 'value'
    ]
];
```

new in v0.7 Nullable
------------------------

```php
    /** 
     * @var Test
     * @Binding(nullable=true)
     */
    private $test;
```

The nullable property define if a null value can be set to entity's property.
The property default value is false. 

V0.7.1 update
-------------

Update Symfony minimum version 4.0 -> 4.1
