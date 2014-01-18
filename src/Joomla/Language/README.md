# The Language Package

## Usage


### Prepare language files

Let's say you want to use English (UK) language pack.

You may find helpful some [files distributed](https://github.com/joomla/joomla-cms/tree/master/language/en-GB) with Joomla CMS:

- `en-GB.ini` - Common language strings such as `JYES`, `ERROR`, `JLOGIN`.
- `en-GB.lib_joomla.ini` - Application language strings like `JLIB_APPLICATION_SAVE_SUCCESS` ('Item successfully saved.').
- `en-GB.localise.php` - To use language-specific methods like `getIgnoredSearchWords`.
- `en-GB.xml` - To use Language metadata definitions (full name, rtl, locale, firstDay).

In the Framework version `1.*` Language handler loads language files from directory defined by `JPATH_ROOT . '/languages/[language tag]/`

In the example below, we will additinally load your application language strings located in file `en-GB.application.ini`.


### Prepare configuration

Assuming configuration in a `JSON` format:

```JSON
{
	"lang": "en-GB",
	"debug": true
}
```

### Set up the Language instance in Web application

_In the example below comments in language tag are replaced by `xx-XX`_

```PHP
use Joomla\Application\AbstractWebApplication;
use Joomla\Language\Language;
use Joomla\Language\Text;

class MyApplication extends AbstractWebApplication
{
	protected $language;

	protected function initialise()
	{
		parent::initialise();

		$language = $this->getLanguage();

		// Load xx-XX/xx-XX.application.ini file
		$language->load('application');
	}

	/**
	 * Get language object.
	 *
	 * @return  Language
	 *
	 * @note    JPATH_ROOT has to be defined.
	 */
	protected function getLanguage()
	{
		if (is_null($this->language))
		{
			// Get language object with the lang tag and debug setting in your configuration
			// This also loads language file /xx-XX/xx-XX.ini and localisation methods /xx-XX/xx-XX.localise.php if available
			$language = Language::getInstance($this->get('language'), $this->get('debug'));

			// Configure Text to use language instance
			Text::setLanguage($language);

			$this->language = $language;
		}

		return $this->language;
	}
}

``

### Use `Text` methods

```PHP
namespace App\Hello\Controller;

use Joomla\Language\Text
use Joomla\Controller\AbstractController;

class HelloController extends AbstractController
{
	public function execute()
	{
		$app = $this->getApplication();

		$translatedString = Text::_('APP_HELLO_WORLD');

		$app->setBody($translatedString);
	}
}

```


### Load component language files

@TODO


## Installation via Composer

Add `"joomla/language": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/language": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/language "dev-master"
```
