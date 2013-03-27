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
