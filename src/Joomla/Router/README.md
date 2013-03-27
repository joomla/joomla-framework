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