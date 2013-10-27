## The Facebook Package

### Using the Facebook Package

The Facebook package is designed to be a straightforward interface for working with Facebook. It is based on the Graph API. You can find documentation on the API at [http://developers.facebook.com/docs/reference/api/](http://developers.facebook.com/docs/reference/api/).

#### Instantiating Facebook

Instantiating Facebook is easy:

```php
use Joomla\Facebook\Facebook;

$facebook = new Facebook;
```

This creates a basic Facebook object that can be used to access publicly available resources on facebook.com which don't require an active access token.

Sometimes it is necessary to provide an active access token with the required permissions. This can be done by instantiating OAuth.

Create a Facebook application at [https://developers.facebook.com/apps](https://developers.facebook.com/apps) in order to request permissions. Instantiate OAuth, passing the Registry options needed. The API key, API secret and callback URL (which is the script's path) from the Facebook application are passed through the Registry object. By default you have to send headers manually in your application, but if you want this to be done automatically you can set Registry's option 'sendheaders' to true.

```php
use Joomla\Facebook\Facebook;
use Joomla\Facebook\OAuth;

$options = array(
    'clientid' => $app_id,
    'clientsecret' => $app_secret,
    'redirecturi' => $callback_url,
    'sendheaders' => true,
    'authmethod' => 'get'
);

$oauth = new OAuth($options);

$facebook = new Facebook($oauth);
```
Now you can authenticate and request the user to authorise your application in order to get an access token, but if you already have an access token stored you can set it to the OAuth object and if it's still valid your application will use it.

```php
// Set the stored access token.
$oauth->setToken($token);

$access_token = $oauth->authenticate();
```

When calling the authenticate() method, your stored access token will be used only if it's valid, a new one will be created if you don't have an access token or if the stored one is not valid. The method will return a valid access token that's going to be used.

Set scope to the OAuth object. Scope is a comma separated list of requested permissions:

```php
$oauth->setScope('read_stream,publish_stream');
```

#### Accessing the Facebook API's objects

The Facebook package has 12 objects of the Graph API currently implemented:
* Album
* Checkin
* Comment
* Event
* Group
* Link
* Note
* Photo
* Post
* Status
* User
* Video

Once a Facebook object has been created, it is simple to use it to access Facebook:

```php
$user = $facebook->user->getFeed($user_id);
```

This will retrieve an array of Post objects containing (up to) the last 25 posts.

#### A More Complete Example

Below is an example demonstrating more of the Facebook package.

```php
use Joomla\Facebook\Facebook;
use Joomla\Facebook\OAuth;

$app_id = "app_id";
$app_secret = "app_secret";
$my_url = 'http://localhost/facebook_test.php';

$options = array(
    'clientid' => $app_id,
    'clientsecret' => $app_secret,
    'redirecturi' => $callback_url,
    'sendheaders' => true,
    'authmethod' => 'get'
);

$oauth = new OAuth($options);
$oauth->authenticate();

$facebook = new Facebook($oauth);

$user = $facebook->user;
$response = $user->getFeed("me");
```

#### More Information

The following resources contain more information
* [Joomla! API Reference](http://api.joomla.org)
* [Facebook Graph API Reference](http://developers.facebook.com/docs/reference/api/)


## Installation via Composer

Add `"joomla/facebook": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/facebook": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/facebook "dev-master"
```
