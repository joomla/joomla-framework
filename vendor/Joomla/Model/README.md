# The Model Package

## Interfaces

### `Model\Model`

`Model\Model` is an interface that requires a class to be implemented with a
`getState` and a `setState` method.

## Classes

# `Model\Base`

#### Construction

The contructor for a new `Model\Base` object takes an optional `Registry` object that
defines the state of the model. If omitted, the contructor defers to the
protected `loadState` method. This method can be overriden in a derived
class and takes the place of the `populateState` method used in the legacy
model class.

#### Usage

The `Model\Base` class is abstract so cannot be used directly. All
requirements of the interface are already satisfied by the base class.

```php

namespace MyApp;

use Joomla\Model\Base;

/**
 * My custom model.
 *
 * @pacakge  Examples
 *
 * @since   1.0
 */
class MyModel extends Base
{
  /**
	 * Get the time.
	 *
	 * @return  integer
	 *
	 * @since   1.0
	 */
	public function getTime()
	{
		return time();
	}
}
```

# `Model\Database`

#### Construction

`Model\Database` is extended from `Model\Base` and the contructor takes an
optional `Database\Driver` object and an optional `Registry` object (the
same one that `JModelBase` uses). If the database object is omitted, the
contructor defers to the protected `loadDb` method which loads the
database object from the platform factory.

#### Usage

The `Model\Database` class is abstract so cannot be used directly. It
forms a base for any model that needs to interact with a database.

```php

namespace MyApp

use Joomla\Model;

/**
 * My custom database model.
 *
 * @package  Examples
 *
 * @since   1.0
 */
class MyDatabaseModel extends Model\Database
{
	/**
	 * Get the content count.
	 *
	 * @return  integer
	 *
	 * @since   1.0
	 * @throws  RuntimeException on database error.
	 */
	public function getCount()
	{		
		// Get the query builder from the internal database object.
		$q = $this->db->getQuery(true);

		// Prepare the query to count the number of content records.
		$q->select('COUNT(*)')->from($q->qn('#__content'));

		$this->db->setQuery($q);

		// Execute and return the result.
		return $this->db->loadResult();
	}
}

try
{
	$model = new MyDatabaseModel;
	$count = $model->getCount();
}
catch (RuntimeException $e)
{
	// Handle database error.
}
```
