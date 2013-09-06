# The OAuth2 Package
## Introduction
The OAuth2 package is a simple package designed to enable OAuth2 authentication.  You can read about the OAuth2 spec at [http://tools.ietf.org/html/rfc6749](http://tools.ietf.org/html/rfc6749).

## Using the Class
### Constructor Parameters
To instantiate the Client class, you must pass in four parameters:

* an array containing options or an object that implements ArrayAccess
* an object that implements the Joomla\HttpInterface contract
* an object that implements the Joomla\InputInterface contract
* an object that implements the Joomla\WebApplicationInterface contract

The above-mentioned interfaces can be found within their respective Joomla-framework package.

#### Options 
The options parameter is meant to contain all of the necessary options for using the class.  These options are:

* 'redirecturi'
* 'clientid'
* 'clientsecret'
* 'tokenurl'
* 'sendHeaders'
* 'authUrl'
* 'scope'
* 'requestparams'
* 'getparam'
* 'state'
* 'authmethod'
* 'userefresh'


### Available Methods
This class has the following public methods available for consumption:

* authenticate() - Get the access token or redict to the authentication URL.
* isAuthenticated() - Verify if the client has been authenticated
* createUrl() - Create the URL for authentication.
* query() - Send a signed Oauth request.
* getOption() - Get an option from the Client instance
* setOption() - Set an option for the Client instance.
* getToken() - Get the access token from the Client instance.
* setToken() - Set the token for the Client instance.
* refreshToken() - Refresh the access token instance.

### Sample

Using the class is rather simple:

```php
// Set it up
$options = array( ... );
$http = new Joomla\Http\Http;
$input = new Joomla\Input\Input;
$application = new Joomla\Test\WebInpector;

// Do work
$client = new Joomla\Oauth2\Client($options, $http, $input, $application);
$token = $client->authenticate();

// If authenticated properly...
echo ($client->isAutheticated()) ? 'Yeppers!' : 'Uh, oh. Something broke.';

```

## Installation via Composer

Add `"joomla/oauth2": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/oauth2": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/oauth2 "dev-master"
```
