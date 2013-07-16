# The Registry Package

```
use Joomla\Registry\Registry;

$registry = new Registry;

// Set a value in the registry.
$registry->set('foo') = 'bar';

// Get a value from the registry;
$value = $registry->get('foo');

```

## Accessing a Registry as an Array

The `Registry` class implements `ArrayAccess` so the properties of the registry can be accessed as an array. Consider the following examples:

```
use Joomla\Registry\Registry;

$registry = new Registry;

// Set a value in the registry.
$registry['foo'] = 'bar';

// Get a value from the registry;
$value = $registry['foo'];

// Check if a key in the registry is set.
if (isset($registry['foo']))
{
	echo 'Say bar.';
}
```

## Installation via Composer

Add `"joomla/registry": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/registry": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/registry "dev-master"
```
