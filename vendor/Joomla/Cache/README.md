# The Cache Package

This cache package complies with the `Psr\Cache` standard.

## Options and General Usage

Each of the cache storage types supports the following options:

* ttl - Time to live.

```
use Joomla\Cache;

$options = new Registry;
$options->set('ttl', 900);

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

```
use Joomla\Cache;

$cache = new Cache\Apc;
```

### File

The **File** cache allows the following additional options:

* file.path - the path where the cache files are to be stored.
* file.locking

```
use Joomla\Cache;

$options = new Registry;
$options->set('file.path', __DIR__ . '/cache');

$cache = new Cache\File($options);
```

### Memcached

```
use Joomla\Cache;

$cache = new Cache\Memcached;
```

### None

```
use Joomla\Cache;

$cache = new Cache\None;
```

### Runtime

```
use Joomla\Cache;

$cache = new Cache\Runtime;
```

### Wincache

```
use Joomla\Cache;

$cache = new Cache\Wincache;
```

### XCache

```
use Joomla\Cache;

$cache = new Cache\XCache;
```
