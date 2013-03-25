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

$archive = new Archive($options)

$archive->extract(__DIR__ . '/archive.zip', __DIR__ . '/destination');
```
