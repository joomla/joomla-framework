# The Application Package

## Initialising Applications

`AbstractApplication` implements an `initialise` method that is called at the end of the constructor. This method is intended to be overriden in derived classes as needed by the developer.

If you are overriding the `__construct` method in your application class, remember to call the parent constructor last.

```php
use Joomla\Application\AbstractApplication;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

class MyApplication extends AbstractApplication
{
	/**
	 * Customer constructor for my application class.
	 *
	 * @param   Input     $input
	 * @param   Registry  $config
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input = null, Registry $config = null, Foo $foo)
	{
		// Do some extra assignment.
		$this->foo = $foo;

		// Call the parent constructor last of all.
		parent::__construct($input, $config);
	}

	/**
	 * Method to run the application routines.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		// Do stuff.
	}

	/**
	 * Custom initialisation for my application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initialise()
	{
		// Do stuff.
		// Note that configuration has been loaded.
	}
}

```

## Logging within Applications

`AbstractApplication` implements the `Psr\Log\LoggerAwareInterface` so is ready for intergrating with an logging package that supports that standard.

The following example shows how you could set up logging in your application using `initialise` method from `AbstractApplication`.

```php
use Joomla\Application\AbstractApplication;
use Monolog\Monolog;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

class MyApplication extends AbstractApplication
{
	/**
	 * Custom initialisation for my application.
	 *
	 * Note that configuration has been loaded.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initialise()
	{
		// Get the file logging path from configuration.
		$logPath = $this->get('logger.path');
		$log = new Logger('MyApp');

		if ($logPath)
		{
			// If the log path is set, configure a file logger.
			$log->pushHandler(new StreamHandler($logPath, Logger::WARNING);
		}
		else
		{
			// If the log path is not set, just use a null logger.
			$log->pushHandler(new NullHandler, Logger::WARNING);
		}

		$this->setLogger($logger);
	}
}

```

The logger variable is private so you must use the `getLogger` method to access it. If a logger has not been initialised, the `getLogger` method will throw an exception.

To check if the logger has been set, use the `hasLogger` method. This will return `true` if the logger has been set.

Consider the following example:

```php
use Joomla\Application\AbstractApplication;

class MyApplication extends AbstractApplication
{
	protected function doExecute()
	{
		// In this case, we always want the logger set.
		$this->getLogger()->logInfo('Performed this {task}', array('task' => $task));

		// Or, in this case logging is optional, so we check if the logger is set first.
		if ($this->get('debug') && $this->hasLogger())
		{
			$this->getLogger()->logDebug('Performed {task}', array('task' => $task));
		}
	}
}
```

## Mocking the Application Package

For more complicated mocking where you need to similate real behaviour, you can use the `Application\Tests\Mocker` class to create robust mock objects.

There are three mocking methods available:

1. `createMockBase` will create a mock for `AbstractApplication`.
2. `createMockCli` will create a mock for `AbstractCliApplication`.
3. `createMockWeb` will create a mock for `AbstractWebApplication`.

```php
use Joomla\Application\Tests\Mocker as AppMocker;

class MyTest extends \PHPUnit_Framework_TestCase
{
	private $instance;

	protected function setUp()
	{
		parent::setUp();

		// Create the mock input object.
		$appMocker = new AppMocker($this);
		$mockApp = $appMocker->createMockWeb();

		// Create the test instance injecting the mock dependency.
		$this->instance = new MyClass($mockApp);
	}
}
```

The `createMockWeb` method will return a mock with the following methods mocked to roughly simulate real behaviour albeit with reduced functionality:

* `appendBody($content)`
* `get($name [, $default])`
* `getBody([$asArray])`
* `getHeaders()`
* `prependBody($content)`
* `set($name, $value)`
* `setBody($content)`
* `setHeader($name, $value [, $replace])`

You can provide customised implementations these methods by creating the following methods in your test class respectively:

* `mockWebAppendBody`
* `mockWebGet`
* `mockWebGetBody`
* `mockWebGetHeaders`
* `mockWebSet`
* `mockWebSetBody`
* `mockWebSetHeader`

## Command Line Applications

The Joomla Framework provides an application class for making command line applications.

An example command line application skeleton:

```php
use Joomla\Application\AbstractCliApplication;

// Bootstrap the autoloader (adjust path as appropriate to your situation).
require_once __DIR__ . '/../vendor/autoload.php';

class MyCli extends AbstractCliApplication
{
	protected function doExecute()
	{
		$this->out('It works');
	}
}

$app = new MyCli;
$app->execute();

```

### Colors for CLI Applications

It is possible to use colors on an ANSI enabled terminal.

```php
use Joomla\Application\AbstractCliApplication;

class MyCli extends AbstractCliApplication
{
	protected function doExecute()
	{
		// Green text
		$this->out('<info>foo</info>');

		// Yellow text
		$this->out('<comment>foo</comment>');

		// Black text on a cyan background
		$this->out('<question>foo</question>');

		// White text on a red background
		$this->out('<error>foo</error>');
	}
}
```

You can also create your own styles.

```php
use Joomla\Application\AbstractCliApplication;
use Joomla\Application\Cli\Colorstyle;

class MyCli extends AbstractCliApplication
{
	/**
	 * Override to initialise the colour styles.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initialise()
	{
		$style = new Colorstyle('yellow', 'red', array('bold', 'blink'));
		$this->getOutput()->addStyle('fire', $style);
	}

	protected function doExecute()
	{
		$this->out('<fire>foo</fire>');
	}
}

```

Available foreground and background colors are: black, red, green, yellow, blue, magenta, cyan and white.

And available options are: bold, underscore, blink and reverse.

You can also set these colors and options inside the tagname:

```php
use Joomla\Application\AbstractCliApplication;

class MyCli extends AbstractCliApplication
{
	protected function doExecute()
	{
		// Green text
		$this->out('<fg=green>foo</fg=green>');

		// Black text on a cyan background
		$this->out('<fg=black;bg=cyan>foo</fg=black;bg=cyan>');

		// Bold text on a yellow background
		$this->out('<bg=yellow;options=bold>foo</bg=yellow;options=bold>');
	}
}
```

## Installation via Composer

Add `"joomla/application": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/application": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/application "dev-master"
```
