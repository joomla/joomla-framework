# The Router Package

## The standard router

### Construction

The standard router optionally takes a `Joomla\Input\Input` object. If not provided, the router will create a new `Input` object which imports its data from `$_REQUEST`.

```
use Joomla\Router\Router;

// Create a default web request router.
$router = new Router;

// Create a router by injecting the input.
$router = new Router($application->getInput());
```

### Adding maps

The purpose of a router is to find a controller based on a routing path. The path could be a URL for a web site, or it could be an end-point for a RESTful web-services API.

The `addMap` method is used to map at routing pattern to a controller.

```
$router = new Router;
$router->addMap('/article/:article_id', '\\Acme\\ArticleController`)
	->addMap('/component/*', '\\Acme\\ComponentFrontController');
```

#### Matching an exact route.

```
$router->addMap('/articles', 'ArticlesController');
$controller = $router->getController('/articles');
```

In this case there is an exact match between the route and the map. An `ArticlesController` would be returned by `getController`.

#### Matching any segment with wildcards

```
$router->addMap('/articles/*', 'ArticlesController');
$controller = $router->getController('/articles/foo/bar');
```

In this case, the router will match any route starting with "/articles/". Anything after that initial prefix is ignored and the controller would have to inspect the route manually to determine the last part of the route.

```
$router->addMap('/articles/*/published', 'PublishedController');
$controller = $router->getController('/articles/foo/bar/published');
```

Wildcards can be used within segments. In the second example if the "/published" suffix is used, a `PublishedController` will be returned instead of an `ArticlesController`.

#### Matching any segments to named variables

```
$router->addMap('/articles/*tags', 'ArticlesController');
$controller = $router->getController('/articles/space,apollo,moon');
```
A star `*` followed by a name will store the wildcard match in a variable of that name. In this case, the router will return an `ArticlesController` but it will inject a variable into the input named `tags` holding the value of anything that came after the prefix. In this example, `tags` will be equal to the value "space,apollo,moon".

```
$controller = $router->getController('/articles/space,apollo,moon/and-stars');
```

Note, however, all the route after the "/articles/" prefix will be matched. In the second case, `tags` would equal "space,apollo,moon/and-stars". This could, however, be used to map a category tree, for example:

```
$controller = $router->getController('/articles/*categories', 'ArticlesController');
$controller = $router->getController('/articles/cat-1/cat-2');
```

In this case the router would return a `ArticlesController` where the input was injected with `categories` with a value of "cat-1/cat-2".

If you need to match the star character exactly, back-quote it, for example:

```
$router->addMap('/articles/\*tags', 'ArticlesTagController');
```

#### Matching one segment to a named variable

```
$router->addMap('/articles/:article_id', 'ArticleController');
$controller = $router->getController('/articles/1');
```
A colon `:` followed by a name will store the value of that segment in a variable of that name. In this case, the router will return an `ArticleController` injecting `article_id` into the input with a value of "1".

Note that a route of `/articles/1/like` would not be matched. The following cases would be required to match this type of route:

```
$router->addMap('/articles/:article_id/like', 'ArticleLikeController');
$router->addMap('/articles/:article_id/*action', 'ArticleActionController');
```

If you need to match the colon character exactly, back-quote it, for example:

```
$router->addMap('/articles/\:tags', 'ArticlesTagController');
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
