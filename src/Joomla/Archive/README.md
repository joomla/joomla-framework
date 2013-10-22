# The Archive Package

The archive package will intelligently load the correct adapter for the specified archive type. It knows how to properly handle the following archive types:

- zip
- tar | tgz | tbz2
- gz | gzip
- bz2 | bzip2

Loading files of the `t*` archive type will uncompress the archive using the appropriate adapter, and then extract via tar.

## Requirements

- PHP 5.3+
- zlib extension for GZip support
- bz2 extension for BZip2 support

## Usage

```php
$options = array('tmp_path' => '/tmp');

$archive = new Joomla\Archive\Archive($options)

$archive->extract(__DIR__ . '/archive.zip', __DIR__ . '/destination');
```

## Overriding Adapters

If you have a custom adapter you would like to use for extracting, this package allows you to override the defaults. Just implement `ExtractableInterface` when creating your adapter, and then use the `setAdapter` method to override.

```php

class MyZipAdapter implements \Joomla\Archive\ExtractableInterface
{
	public static function isSupported()
	{
		// Do you test
		return true;
	}

	public function extract($archive, $destination)
	{
		// Your code
	}
}

$archive = new Archive;

// You need to pass the fully qualified class name.
$archive->setAdapter('zip', '\\MyZipAdapter');

// This will use your
$archive->extract('archive.zip', 'destination');
```

## Installation via Composer

Add `"joomla/archive": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/archive": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/archive "dev-master"
```
