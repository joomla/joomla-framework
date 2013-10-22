## The LinkedIn Package

### Using the LinkedIn Package

The LinkedIn package is designed to be a straightforward interface for working with LinkedIn. It is based on the REST API. You can find documentation on the API at [http://developer.linkedin.com/rest](http://developer.linkedin.com/rest).

#### Instantiating Linkedin

Instantiating Linkedin is easy:

```php
use Joomla\Linkedin\Linkedin;

$linkedin = new Linkedin;
```

This creates a basic Linkedin object that can be used to access resources on linkedin.com, using an active access token.

Generating an access token can be done by instantiating OAuth.

Create a LinkedIn application at [https://www.linkedin.com/secure/developer](https://www.linkedin.com/secure/developer) in order to request permissions.
Instantiate OAuth, passing the Registry options needed. By default you have to set and send headers manually in your application, but if you want this to be done automatically you can set Registry option 'sendheaders' to true.

```php
use Joomla\Linkedin\Linkedin;
use Joomla\Linkedin\OAuth;
use Joomla\Registry\Registry;

$options = new Registry;
$options->set('consumer_key', $consumer_key);
$options->set('consumer_secret', $consumer_secret);
$options->set('callback', $callback_url);
$options->set('sendheaders', true);
$oauth = new OAuth($options);

$linkedin = new Linkedin($oauth);
```

Now you can authenticate and request the user to authorise your application in order to get an access token, but if you already have an access token stored you can set it to the OAuth object and if it's still valid your application will use it.

```php
// Set the stored access token.
$oauth->setToken($token);

$access_token = $oauth->authenticate();
```

When calling the authenticate() method, your stored access token will be used only if it's valid, a new one will be created if you don't have an access token or if the stored one is not valid. The method will return a valid access token that's going to be used.

#### Accessing the LinkedIn API's objects

The LinkedIn package covers almost all Resources of the REST API:
* Communications object interacts with Communications resources.
* Companies object interacts with Companies resources.
* Groups object interacts with Groups resources.
* Jobs object interacts with Jobs resources.
* People object interacts with People and Connections resources.
* Stream object interacts with Social Stream resources.

Once a Linkedin object has been created, it is simple to use it to access LinkedIn:

```php
$people = $linkedin->people->getConnections();
```

This will retrieve a list of connections for a user who has granted access to his/her account.

#### A More Complete Example

Below is an example demonstrating more of the Linkedin package.

```php
use Joomla\Linkedin\Linkedin;
use Joomla\Linkedin\OAuth;
use Joomla\Registry\Registry;

$app_id = "app_id";
$app_secret = "app_secret";
$my_url = 'http://localhost/linkedin_test.php';


$options = new Registry;
$options->set('consumer_key', $key);
$options->set('consumer_secret', $secret);
$options->set('callback', $my_url);
$options->set('sendheaders', true);

$oauth = new OAuth($options);
$oauth->authenticate();

$linkedin = new Linkedin($oauth);

$people = $linkedin->people;
$response = $people->getProfile();
```

#### More Information

The following resources contain more information:
* [Joomla! API Reference](http://api.joomla.org)
* [LinkedIn REST API Reference](http://developer.linkedin.com/rest)


## Installation via Composer

Add `"joomla/linkedin": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/linkedin": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/linkedin "dev-master"
```
