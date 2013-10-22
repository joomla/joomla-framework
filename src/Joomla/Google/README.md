## The Google Package

### Using the Google Package

The Google package is designed to be a straightforward interface for working with various Google APIs. You can find a list of APIs and documentation for each API at [https://developers.google.com/products/.](https://developers.google.com/products/)

#### Instantiating Google

Instantiating Google is easy:

```php
use Joomla\Google\Google;

$google = new Google;
```

This creates a generic Google object that can be used to instantiate objects for specific Google APIs.

Sometimes it is necessary to specify additional options. This can be done by injecting in a Registry object with your preferred options:

```php
use Joomla\Google\Google;
use Joomla\Registry\Registry;

$options = new Registry;
$options->set('clientid', 'google_client_id.apps.googleusercontent.com');
$options->set('clientsecret', 'google_client_secret');

$google = new Google($options);
```

#### Accessing the JGoogle APIs

The Google Package divides APIs into two types: data APIs and embed APIs. Data APIs use `Joomla\Http` to send and receive data from Google. Embed APIs output HTML, JavaScript, and XML in order to embed information from Google in a webpage.

The Google package is still incomplete, but there are five object APIs that have currently been implemented:

Data: Google Calendar, Google AdSense, Google Picasa

Embed: Google Maps, Google Analytics

Once a Google object has been created, it is simple to use it to create objects for each individual API:

```php
$calendar = $google->data('calendar');
```

or

```php
$analytics = $google->data('analytics');
```

#### Using an API

See below for an example demonstrating the use of the Calendar API:

```php
use Joomla\Google\Google;
use Joomla\Registry\Registry;

$options = new Registry;

// Client ID and Client Secret can be obtained  through the Google API Console (https://code.google.com/apis/console/).
$options->set('clientid', 'google_client_id.apps.googleusercontent.com');
$options->set('clientsecret', 'google_client_secret');
$options->set('redirecturi', JURI::current());

$google = new Google($options);

// Get a calendar API object
$calendar = $google->data('calendar');

// If the client hasn't been authenticated via OAuth yet, redirect to the appropriate URL and terminate the program
if (!$calendar->isAuth())
{
	JResponse::sendHeaders();
	die();
}

// Create a new Google Calendar called "Hello World."
$calendar->createCalendar('Hello World');
```

#### More Information

The following resources contain more information:[Joomla! API Reference](http://api.joomla.org), [Google Developers Homepage](https://developers.google.com/)


## Installation via Composer

Add `"joomla/google": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/google": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/google "dev-master"
```
