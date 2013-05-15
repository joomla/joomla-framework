## The Github Package

### Using the Github Package

The Github package is designed to be a straightforward interface for
working with Github. It is based on version 3 of the Github API. You can
find documentation on the API at
[http://developer.github.com/v3/.](http://developer.github.com/v3/)

Github is built upon the Http package which provides an easy way to
consume URLs and web services in a transport independent way. JHttp
currently supports streams, sockets and CURL. It is possible to create a
custom context and inject it into the Github class if one so desires.

#### Instantiating Github

Instantiating Github is easy:

```php
use Joomla\Github\Github;
$github = new Github;
```

This creates a basic Github object that can be used to access
publically available resources on github.com.

Sometimes it is necessary to specify additional options. This can be
done by injecting in a Registry object with your preferred options:

```php
use Joomla\Github\Github;
use Joomla\Registry\Registry;
$options = new Registry;
$options->set('api.username', 'github_username');
$options->set('api.password', 'github_password');

$github = new Github($options);
```

#### Accessing the Github APIs

The Github package is still incomplete, but there are four object APIs
that have currently been implemented:Gists, Issues, References, Pull
Requests

Once a Github object has been created, it is simple to use it to access
Github:

```php
$pullRequests = $github->pulls->getList('joomla', 'joomla-framework');
```

This will retrieve a list of all open pull requests in the specified
repository.

#### A More Complete Example

See below for an example demonstrating more of the Github package:

```php
use Joomla\Github\Github;
use Joomla\Registry\Registry;
$options = new Registry();
$options->set('api.username', 'github_username');
$options->set('api.password', 'github_password');
$options->set('api.url', 'http://myhostedgithub.example.com');

$github = new Github($options);

// get a list of all the user's issues
$issues = $github->issues->getList();

$issueSummary = array();

foreach ($issues as $issue)
{
	$issueSummary[] = '+ ' . $issue->title;
}

$summary = implode("\n", $issueSummary);

$github->gists->create(array('issue_summary.txt' => $summary));
```

#### More Information

The following resources contain more information:  [Joomla! API
Reference](http://api.joomla.org), [Github API
Reference](http://developer.github.com).


## Installation via Composer

Add `"joomla/github": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/github": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/github "dev-master"
```
