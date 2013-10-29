# The DI Package

The Dependency Injection package for Joomla provides a simple IoC Container for your application. Dependency Injection allows you the developer to control the construction and lifecycle of your objects, rather than leaving that control to the classes themselves. Instead of hard coding a class's dependencies within the class `__construct()` method, you instead provide to a class the dependencies it requires as arguments to its constructor. This helps to decrease hard dependencies and to create loosely coupled code.

Read more about [why you should be using dependency injection](docs/why-dependency-injection.md).

An Inversion of Control (IoC) Container helps you to manage these dependencies in a controlled fashion.

## Using the Container

### Creating a Container

@TODO
- Creation
- Creating a Child Container

### Setting an Item

@TODO
- Basic
- Shared
- Protected
- Binding an implementation to an interface

### Item Aliases

@TODO
- Setting an alias
- Resolving an alias
- Resolving an alias that hasn't been set

### Getting an Item

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
