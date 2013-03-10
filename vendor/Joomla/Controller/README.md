# The Controller Package

## Interfaces

### `Controller\Controller`

`Controller\Controller` is an interface that requires a class to be implemented with
an `execute`, a `getApplication` and a `getInput` method.

## Classes

### `Controller\Base`

#### Construction

The constructor for `Controller\Base` takes an optional `Joomla\Input\Input` object and
an optional `Joomla\Applciation\Base` object. One or the other can be omitted but using `getApplication` or `getInput` without setting them will throw an exception.

#### Usage

The `Controller\Base` class is abstract so cannot be used directly. The
derived class must implement the execute method to satisfy the interface
requirements. Note that the execute method no longer takes a "task"
argument as each controller class. Multi-task controllers are still
possible by overriding the execute method in derived classes. Each
controller class should do just one sort of 'thing', such as saving,
deleting, checking in, checking out and so on. However, controllers, or
even models and views, have the liberty of invoking other controllers to
allow for HMVC architectures.

```php
namespace Examples;

use Joomla\Application;
use Joomla\Input;

/**
 * My custom controller.
 *
 * @since  1.0
 */
class MyController extends Controller\Base
{
	/**
	 * Executes the controller.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		echo time();
	}
}

// We'll assume we've already defined an application in this namespace. 
$app = new ExampleApplication;
$input = new Input\Input;

// Instantiate the controller.
$controller = new MyController($input, $app);

// Print the time.
$controller->execute();
```

#### Serialization

The `Controller\Base` class implements `Serializable`. When serializing,
only the input property is serialized. When unserializing, the input
variable is unserialized and the internal application property is loaded
at runtime.
