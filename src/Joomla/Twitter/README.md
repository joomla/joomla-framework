## The Twitter Package

### Using the Twitter Package

The Twitter package is designed to be a straightforward interface for working with Twitter. It is based on the REST API. You can find documentation on the API at [https://dev.twitter.com/docs/api](https://dev.twitter.com/docs/api).

#### Instantiating Twitter

Instantiating Twitter is easy:

```php
use Joomla\Twitter\Twitter;

$twitter = new Twitter;
```

This creates a basic Twitter object that can be used to access resources on twitter.com, using an active access token.

Generating an access token can be done by instantiating OAuth.

Create a Twitter application at [https://dev.twitter.com/apps](https://dev.twitter.com/apps) in order to request permissions. Instantiate OAuth, passing the options needed. By default you have to set and send headers manually in your application, but if you want this to be done automatically you can set option 'sendheaders' to true.

```php
use Joomla\Twitter\Twitter;
use Joomla\Twitter\OAuth;

$options = array(
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret,
    'callback' => $callback_url,
    'sendheaders' => true
);

$oauth = new OAuth($options);

$twitter = new Twitter($oauth);
```

Now you can authenticate and request the user to authorise your application in order to get an access token, but if you already have an access token stored you can set it to the OAuth object and if it's still valid your application will use it.

```php
// Set the stored access token.
$oauth->setToken($token);

$access_token = $oauth->authenticate();
```

When calling the authenticate() method, your stored access token will be used only if it's valid, a new one will be created if you don't have an access token or if the stored one is not valid. The method will return a valid access token that's going to be used.

#### Accessing the Twitter API's objects

The Twitter package covers almost all Resources of the REST API 1.1:
* Block object interacts with Block resources.
* DirectMessages object interacts with Direct Messages resources.
* Favorites object interacts with Favorites resources.
* Friends object interacts with Friends and Followers resources.
* Help object interacts with Help resources.
* Lists object interacts with Lists resources.
* Places object interacts with Places and Geo resources.
* Profile object interacts with some resources from Accounts.
* Search object interacts with Search and Saved Searches resources.
* Statuses object interacts with Timelines and Tweets resources.
* Trends object interacts with Trends resources.
* Users object interacts with Users and Suggested Users resources.

Once a Twitter object has been created, it is simple to use it to access Twitter:

```php
$users = $twitter->users->getUser($user);
```

This will retrieve extended information of a given user, specified by ID or screen name.

#### A More Complete Example

Below is an example demonstrating more of the Twitter package.

```php
use Joomla\Twitter\Twitter;
use Joomla\Twitter\OAuth;

$app_id = "app_id";
$app_secret = "app_secret";
$my_url = 'http://localhost/twitter_test.php';

$options = array(
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret,
    'callback' => $callback_url,
    'sendheaders' => true
);

$oauth = new OAuth($options);
$oauth->authenticate();

$twitter = new Twitter($oauth);

$statuses = $twitter->statuses;
$response = $statuses->tweet("The Twitter Package is amazing #joomla");
```

#### More Information

The following resources contain more information:
* [Joomla! API Reference](http://api.joomla.org)
* [Twitter REST API Reference](https://dev.twitter.com/docs/api)
* [Web Application using JTwitter package](https://gist.github.com/3258852)

## Installation via Composer

Add `"joomla/twitter": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/twitter": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/twitter "dev-master"
```
