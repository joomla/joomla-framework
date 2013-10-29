# The DI Package

The Dependency Injection package for Joomla provides a simple IoC Container for your application.
Dependency Injection allows you the developer to control the construction and lifecycle of your objects,
rather than leaving that control to the classes themselves. Instead of hard coding a class's dependencies
within the class `__construct()` method, you instead provide to a class the dependencies it requires as
arguments to its constructor. This helps to decrease hard dependencies and to create loosely coupled code.

Read more about [why you should be using dependency injection](docs/why-dependency-injection.md).

An Inversion of Control (IoC) Container helps you to manage these dependencies in a controlled fashion.

## Using the Container

### Creating a Container

Creating a container usually happens very early in the application lifecycle. For a Joomla MVC app, this
typically happens in the application's `doExecute` method. This allows your application access to the DI
Container, which you can then use within the app class to build your controllers and their dependencies.

```php
namespace My\App;

use Joomla\DI\Container;
use Joomla\Application\AbstractWebApplication;

class WebApp extends AbstractWebApplication
{
    protected $container;

    // ...snip

    protected function doExecute()
    {
        $this->container = new Container;

        // ...snip
    }
}
```

Another feature of the container is the ability to create a child container with a different resolution
scope. This allows you to easily override an interface binding for a specific controller, without
destroying the resolution scope for the rest of the classes using the container. A child container will
search recursively through it's parent containers to resolve all the required dependencies.

```php
use Joomla\DI\Container;

$container->set('Some\Interface\I\NeedInterface', 'My\App\InterfaceImplementation');
// Application executes... Come to a class that needs a different implementation.
$container->createChild();
$container->set('Some\Interface\I\NeedInterface', 'My\Other\InterfaceImplementation');
```

### Setting an Item

Setting an item within the container is very straightforward. You pass the `set` method a string `$key`
and a `$value`, which can be pretty much anything. If the `$value` is an anonymous function or a `Closure`,
that value will be set as the resolving callback for the `$key`. If it is anything else (an instantiated
object, array, integer, serialized controller, etc) it will be wrapped in a closure and that closure will
be set as the resolving callback for the `$key`.

> If the `$value` you are setting is a closure, it will receive a single function argument,
> the calling container. This allows access to the container within your resolving callback.

```php
// Assume a created $container
$container->set('foo', 'bar');

$container->set('something', new Something);
// etc
```

When setting items in the container, you are allowed to specify whether the item is supposed to be a
shared or protected item. A shared item means that when you get an item from the container, the resolving
callback will be fired once, and the value will be stored and used on every subsequent request for that
item. The other option, protected, is a special status that you can use to prevent others from overwriting
the item down the line. A good example for this would be a global config that you don't want to be
overwritten. The third option is that you can both share AND protect an item. A good use case for this would
be a database connection that you only want one of, and you don't want it to be overwritten.

```php
// Assume a created $container
$container->share('foo', function () {
    // some expensive $stuff;

    return $stuff;
});

$container->protect('bar', function (Container $c) {
    // Don't overwrite my db connection.
    $config = $c->get('config');

    return new DatabaseDriver($config['database']);
});
```

> Both the `protect` and `share` methods take an optional third parameter. If set to `true`, it will
> tell the container to both protect _and_ share the item. (Or share _and_ protect, depending on
> the origin method you call. Essentially it's the same thing.)

The most powerful feature of setting an item in the container is the ability to bind an implementation
to an interface. This is useful when using the container to build your app objects. You can typehint
against an interface, and when the object gets built, the container will pass your implementation.

@TODO
- Interface binding usage example

### Item Aliases

Any item set in the container can be aliased. This allows you to create an object that is a named
dependency for object resolution, but also have a "shortcut" access to the item from the container.

```php
// Assume a created $container
$container->set('Really\Long\ConfigClassName', function () {});

$container->alias('config', 'Really\Long\ConfigClassName');

$container->get('config'); // Returns the value set on the aliased key.
```

@TODO
- Resolving an alias
- Resolving an alias that hasn't been set

### Getting an Item

At its most basic level the DI Container is a registry that holds keys and values. When you set
an item on the container, you can retrieve it by passing the same `$key` to the `get` method that
you did when you set the method in the container. (This is where aliases can come in handy.)

@TODO
- Fetch shared item
- fetch unshared item (resolves each time)
- getNewInstance
- Recurses into parent containers

### Instantiate an object from the Container

@TODO
- Automatic constructor injection
- Build Shared Object
- DependencyResolutionException

### Extending an Item

@TODO
- Detailed explanation
- Decorator pattern
- Exception is thrown

### Service Providers

@TODO
- ServiceProviderInterface
- Example Service Provider

### Container Aware Objects

@TODO
- How to make an object container aware
