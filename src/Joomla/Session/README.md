# The Session Package

## Session Storage Types

### Gaememcached


**Gaememcached** is the memcached service provided to applications deployed via Google App Engine.  See https://developers.google.com/appengine/docs/php/memcache/ for details.  When run in the development environment(via the Google App Engine SDK for PHP) the memcached extension cannot be loaded, so calls to extension_loaded('memcached') will fail.  Thus the need for a seperate class.  This class also eliminates method/function calls which are not supported when run under Google App Engine.


## Installation via Composer

Add `"joomla/session": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/session": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/session "dev-master"
```
