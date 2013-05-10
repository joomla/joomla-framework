# The DI Package

The Dependency Injection package for Joomla provides a simple IoC Container for your application. Dependency Injection allows you the developer to control the construction and lifecycle of your objects, rather than leaving that control to the classes themselves. Instead of hard coding a class's dependencies within the class `__construct()` method, you instead provide to a class the dependencies it requires. This helps to lower inter-class dependencies and to create loosely coupled code.

Read more about [why you should be using dependency injection](docs/why-dependency-injection.md).

An Inversion of Control (IoC) Container helps you to manage these dependencies in a controlled fashion.