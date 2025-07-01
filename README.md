# BindingBundle

This bundle provides a binding mechanism from array to Entity with Symfony.

## Requirements

- PHP 8.2 or higher
- Symfony 7.0 or higher

## Installation

Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/binding-bundle
```

Register the bundle in your `config/bundles.php` file:

```php
return [
    // ...
    SOW\BindingBundle\SOWBindingBundle::class => ['all' => true],
];
```

## Configuration

Configure the bundle in your `config/packages/sow_binding.yaml` file:

```yaml
# Default configuration
sow_binding:
    # Use attribute binding method (required for PHP 8+)
    binding_method: attribute

    # Optional: Override the default attribute class
    # attribute_class_name: 'SOW\BindingBundle\Attribute\Binding'
```

## Usage

### Define binding properties in your entity

```php
#[Binding(key: "lastname", setter: "setLastname", type: "string", min: 2, max: 255)]
private string $lastname = '';

#[Binding(key: "firstname")]
private string $firstname = '';

#[Binding(key: "age", type: "integer", min: 0, max: 120)]
private int $age = 0;

#[Binding(key: "user_email", )]
private string $userEmail = '';

#[Binding(key: "test", type: "App\Entity\Test", nullable: true)]
private ?Test $test = null;
```

### Binding options

- `key`: The array value's key (required)
- `setter`: Used if you want to use another setter method name
- `type`: Used for type checking. A BinderTypeException is thrown if the type doesn't match
- `min`/`max`: Check if the value is in the defined range (works with numbers, string length, and array count)
- `nullable`: Defines if a null value can be set to the entity's property (default: false)

### Use Binder service to bind an array to an entity

```php
public function __construct(BinderInterface $binder)
{
    $this->binder = $binder;
}

function bind(BindableEntity $be, array $data): BindableEntity
{
    // Example data
    // $data = [
    //     'lastname' => 'Doe', 
    //     'firstname' => 'John', 
    //     'age' => 20, 
    //     'userEmail' => 'some.email@mail.com',
    //     'test' => [
    //         'testProps1' => 'value',
    //         'testProps2' => 'value'
    //     ]
    // ];

    $this->binder->bind($be, $data);
    // Or with include/exclude options:
    // $this->binder->bind($be, $data, ['firstname', 'lastname'], ['age']);

    return $be;
}
```

### Advanced binding options

You can use include/exclude arrays to control which properties are bound:

```php
public function bind(&$object, array $params = [], array $include = [], array $exclude = [])
```

- `$include`: A key array required in `$params`. If one or more keys are missing, an exception is thrown
- `$exclude`: A key array ignored in `$params`. No exception is thrown if a key is present

## Changelog

For version history and detailed changes, see the [changelog](./changelog) directory.
