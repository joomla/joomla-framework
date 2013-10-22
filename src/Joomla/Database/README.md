# The Database Package

## Introduction

The *Database* package is designed to manage the operations of data management through the use of a generic database engine.

```php
// Example for initialising a database driver in a custom application class.

use Joomla\Application\AbstractApplication;
use Joomla\Database;

class MyApplication extends AbstractApplication
{
	/**
	 * Database driver.
	 *
	 * @var    Database\DatabaseDriver
	 * @since  1.0
	 */
	protected $db;

	protected function doExecute()
	{
		// Do stuff
	}

	protected function initialise()
	{
		// Make the database driver.
		$dbFactory = new Database\DatabaseFactory;

		$this->db = $dbFactory->getDriver(
			$this->get('database.driver'),
			array(
				'host' => $this->get('database.host'),
				'user' => $this->get('database.user'),
				'password' => $this->get('database.password'),
				'port' => $this->get('database.port'),
				'socket' => $this->get('database.socket'),
				'database' => $this->get('database.name'),
			)
		);
	}
}
```

## Escaping Strings and Input

Strings must be escaped before using them in queries (never trust any variable input, even if it comes from a previous database query from your own data source). This can be done using the `escape` and the `quote` method.

The `escape` method will generally backslash unsafe characters (unually quote characters but it depends on the database engine). It also allows for optional escaping of additional characters (such as the underscore or percent when used in conjunction with a `LIKE` clause).

The `quote` method will escape a string and wrap it in quotes, however, the escaping can be turned off which is desirable in some situations. The `quote` method will also accept an array of strings and return an array quoted and escaped (unless turned off) string.

```php
function search($title)
{
	// Get the database driver from the factory, or by some other suitable means.
	$db = DatabaseDriver::getInstance($options);

	// Search for an exact match of the title, correctly sanitising the untrusted input.
	$sql1 = 'SELECT * FROM #__content WHERE title = ' . $db->quote($title);

	// Special treatment for a LIKE clause.
	$search = $db->quote($db->escape($title, true) . '%', false);
	$sql2 = 'SELECT * FROM #__content WHERE title LIKE ' . $search;

	if (is_array($title))
	{
		$sql3 = 'SELECT * FROM #__content WHERE title IN ('
			. implode(',', $db->quote($title)) . ')';
	}

	// Do the database calls.
}
```

In the first case, the title variable is simply escaped and quoted. Any quote characters in the title string will be prepended with a backslash and the whole string will be wrapped in quotes.

In the second case, the example shows how to treat a search string that will be used in a `LIKE` clause. In this case, the title variable is manually escaped using `escape` with a second argument of `true`. This will force other special characters to be escaped (otherwise you could set youself up for serious performance problems if the user includes too many wildcards). Then, the result is passed to the `quote` method but escaping is turned off (because it has already been done manually).

In the third case, the title variable is an array so the whole array can be passed to the `quote` method (this saves using a closure and a )

Shorthand versions are  available the these methods:

* `q` can be used instead of `quote`
* `qn` can be used instead of `quoteName`
* `e` can be used instead of `escape`

These shorthand versions are also available when using the `Database\DatabaseQuery` class.

## Iterating Over Results

The `Database\DatabaseIterator` class allows iteration over database results

```php
$db = DatabaseDriver::getInstance($options);
$iterator = $db->setQuery(
	$db->getQuery(true)->select('*')->from('#__content')
)->getIterator();

foreach ($iterator as $row)
{
    // Deal with $row
}
```

It allows also to count the results.

```php
$count = count($iterator);
```
## Logging

`Database\DatabaseDriver` implements the `Psr\Log\LoggerAwareInterface` so is ready for intergrating with a logging package that supports that standard.

Drivers log all errors with a log level of `LogLevel::ERROR`.

If debugging is enabled (using `setDebug(true)`), all queries are logged with a log level of `LogLevel::DEBUG`. The context of the log include:

* **sql** : The query that was executed.
* **category** : A value of "databasequery" is used.


## Installation via Composer

Add `"joomla/database": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/database": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/database "dev-master"
```
