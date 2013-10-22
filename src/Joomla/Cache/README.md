# The Cache Package

This cache package complies with the proposed `Psr\Cache` standard.

## Options and General Usage

Following option as available across a cache storage types:

* ttl - Time to live.

```php
use Joomla\Cache;

$options = array(
	'ttl' => 900,
);

$cache = new Cache\Runtime($options);

// Set a value in the cache.
$cache->set('key', 'value');

// Get the value back.
$value = $cache->get('key')->getValue();

// Remove the item from the cache.
$cache->remove('key');

// Clear all the items from the cache.
$cache->clear();

// Get multiple values from the cache at once.
$values = $cache->getMultiple(array('key1', 'key2'));

// Set multiple values from the cache at once.
$values = $cache->setMultiple(array('key1' => 'value1, 'key2' => 'value2'));

// Remove multiple values from the cache at once.
$values = $cache->removeMultiple(array('key1', 'key2'));
```

## Cache Storage Types

The following storage types are supported.

### Apc

```php
use Joomla\Cache;

$cache = new Cache\Apc;
```

### File

The **File** cache allows the following additional options:

* file.path - the path where the cache files are to be stored.
* file.locking

```php
use Joomla\Cache;

$options = array(
	'file.path' => __DIR__ . '/cache',
);

$cache = new Cache\File($options);
```

### Memcached

```php
use Joomla\Cache;

$cache = new Cache\Memcached;
```

### None

```php
use Joomla\Cache;

$cache = new Cache\None;
```

### Runtime

```php
use Joomla\Cache;

$cache = new Cache\Runtime;
```

### Wincache

```php
use Joomla\Cache;

$cache = new Cache\Wincache;
```

### XCache

```php
use Joomla\Cache;

$cache = new Cache\XCache;
```

## Test Mocking

The `Cache` package provide a **PHPUnit** helper to mock a `Cache\Cache` object or an `Cache\Item` object. You can include your own optional overrides in the test class for the following methods:

* `Cache\Cache::get`: Add a method called `mockCacheGet` to your test class. If omitted, the helper will return a default mock for the `Cache\Item` class.
* `Cache\Item::getValue`: Add a method called `mockCacheItemGetValue` to your test class. If omitted, the mock `Cache\Item` will return `"value"` when this method is called.
* `Cache\Item::isHit`: Add a method called `mockCacheItemIsHit` to your test class. If omitted, the mock `Cache\Item` will return `false` when this method is called.

```php
use Joomla\Cache\Tests\Mocker as CacheMocker;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
	private $instance;

	//
	// The following mocking methods are optional.
	//

	/**
	 * Callback to mock the Cache\Item::getValue method.
	 *
	 * @return  string
	 */
	public function mockCacheItemGetValue()
	{
		// This is the default handling.
		// You can override this method to provide a custom return value.
		return 'value';
	}

	/**
	 * Callback to mock the Cache\Item::isHit method.
	 *
	 * @return  boolean
	 */
	public function mockCacheItemIsHit()
	{
		// This is the default handling.
		// You can override this method to provide a custom return value.
		return false;
	}

	/**
	 * Callback to mock the Cache\Cache::get method.
	 *
	 * @param   string  $text  The input text.
	 *
	 * @return  string
	 */
	public function mockCacheGet($key)
	{
		// This is the default handling.
		// You can override this method to provide a custom return value.
		return $this->createMockItem();
	}

	protected function setUp()
	{
		parent::setUp();

		$mocker = new CacheMocker($this);

		$this->instance = new SomeClass($mocker->createMockCache());
	}
}
```

## Installation via Composer

Add `"joomla/cache": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/cache": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/cache "dev-master"
```
