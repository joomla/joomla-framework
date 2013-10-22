# The Test Package

This package is a collection of tools that make some of the jobs of unit testing easier.

## TestHelper

`Joomla\Test\TestHelper` is a static helper class that can be used to take some of the pain out of repetitive tasks whilst unit testing with PHPUnit.

### Mocking

There are two methods that help with PHPUnit mock objects.

#### `TestHelper::assignMockCallbacks`

This helper method provides an easy way to configure mock callbacks in bulk.

```php
use Joomla\Test\TestHelper;

class FooTest extends \PHPUnit_Framework_TestCase
{
	public function testFoo()
	{
		// Create the mock.
		$mockFoo = $this->getMock(
			'Foo',
			// Methods array.
			array(),
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		$mockCallbacks = array(
			// 'Method Name' => <callback>
			'method1' => array('\mockFoo', 'method1'),
			'method2' => array($this, 'mockMethod2'),
		);

		TestHelper::assignMockReturns($mockFoo, $this, $mockReturns);
	}

	public function mockMethod2($value)
	{
		return strtolower($value);
	}
}

```

#### `TestHelper::assignMockReturns`

This helper method provides an easy way to configure mock returns values in bulk.

```php
use Joomla\Test\TestHelper;

class FooTest extends \PHPUnit_Framework_TestCase
{
	public function testFoo()
	{
		// Create the mock.
		$mockFoo = $this->getMock(
			'Foo',
			// Methods array.
			array(),
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		$mockReturns = array(
			// 'Method Name' => 'Canned return value'
			'method1' => 'canned result 1',
			'method2' => 'canned result 2',
			'method3' => 'canned result 3',
		);

		TestHelper::assignMockReturns($mockFoo, $this, $mockReturns);
	}
}

```

### Reflection

There are three methods that help with reflection.

#### `TestHelper::getValue`

The `TestHelper::getValue` method allows you to get the value of any protected or private property.

```php
use Joomla\Test\TestHelper;

class FooTest extends \PHPUnit_Framework_TestCase
{
	public function testFoo()
	{
		$instance = new \Foo;

		// Get the value of a protected `bar` property.
		$value = TestHelper::getValue($instance, 'bar');
	}
}

```

This method should be used sparingly. It is usually more appropriate to use PHPunit's `assertAttribute*` methods.

#### `TestHelper::setValue`

The `TestHelper::setValue` method allows you to set the value of any protected or private property.

```php
use Joomla\Test\TestHelper;

class FooTest extends \PHPUnit_Framework_TestCase
{
	public function testFoo()
	{
		$instance = new \Foo;

		// Set the value of a protected `bar` property.
		TestHelper::getValue($instance, 'bar', 'New Value');
	}
}

```

This method is useful for injecting values into an object for the purpose of testing getter methods.

#### `TestHelper::invoke`

The `TestHelper::invoke` method allow you to invoke any protected or private method. After specifying the object and the method name, any remaining arguments are passed to the method being invoked.

```php
use Joomla\Test\TestHelper;

class FooTest extends \PHPUnit_Framework_TestCase
{
	public function testFoo()
	{
		$instance = new \Foo;

		// Invoke the protected `bar` method.
		$value1 = TestHelper::invoke($instance, 'bar');

		// Invoke the protected `bar` method with arguments.
		$value2 = TestHelper::invoke($instance, 'bar', 'arg1', 'arg2');
	}
}
```

## Installation via Composer

Add `"joomla/test": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/test": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/test "dev-master"
```
