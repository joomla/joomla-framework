# The Router Package

```
use Joomla\Router;

// Code to initialise the application variable.

$apiVersion = 1;

$router = new Router\Base($app);

// Set a default controller.
$router->setDefaultController('\Controller');

// Set a prefix for the controllers.
$router->setControllerPrefix('\Vnd\V' . $apiVersion . '\\');

// Add a routing map.
$router->addMap('article/:article_id');

// Get the controller.
$controller = $router->route('/article/42');
```

## Installation via Composer

Add `"joomla/router": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/router": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/router "dev-master"
```
