# Why Registry Runtime

Many of the Joomla! Framework classes depend on specific PHP classes, functions, or extensions to be loaded in order to function.  This makes them difficult to unit test in environments where those classes, functions, or extensions do not exist.  By the same token, difficult to unit test the error or exception returned when invoked in an environment that is  not supported.  By moving calls to function_exists() and other functions which investigate the runtime environment - and invoking them from a Registry Runtime object instead, we can provide mock objects to return the values we want for testing.

```php
namespace Joomla\Cache

class Memcached extends Cache
{

	public function __construct()
	{
		if (!self::isSupported)
		{
			//throw an exception of some sort
		}
	}

	static public function isSupported()
	{
		return \extension_loaded('memcached');
	}
}
'''

### The Situation

If an application attempts to create a Memcached object when the extension is not loaded, the constructor will throw an exception.

### The Problem

When unit testing, if the Memcached extension is not loaded, none of the unit tests for Memcached can be invoked - even if we provide it with a mock interface to Memcached.  By the same token, if the Memcached extension is loaded, then we cannot verify that the constructor will work properly when it is not loaded.

### The Solution

Move system calls such as this to a seperate class which can be invoked as an object.  Then this object can be passed to the problematic class in the constructor in order to run our unit tests.

```php
namespace Joomla\Cache

use Joomla\Registry\Runtime

class Memcached extends Cache
{

	/**
	 * runtime
	 *   A runtime registry to use to check the runtime
	 *
	 * @var    Runtime
	 */
	static public $runtime;

	/**
	 * Constructor
	 *
	 * @param   Runtime  $runtime  a Runtime Registry
	 */
	public function __construct($runtime = false)
	{

		if (!$runtime)
        {
        	$runtime = Runtime::getInstance('cache');;
        }

        if (!self::isSupported($runtime)
		{
			//throw an exception of some sort
		}
	}

	/**
	 * getRuntime
	 *
	 * @param   string  $instanceName
	 */
	static public function getRuntime($instanceName = __CLASS__)
	{

		if (isset(self::$runtime))
        {
        	return self::$runtime;
        }

        	return Runtime::getInstance($instanceName);;
    }


	/**
	 * isSupported
	 *
	 * @param   Runtime  $runtime  a Runtime Registry
	 */
	static public function isSupported($runtime)
	{
		if (!$runtime)
        {
        	$runtime = self::getRuntime('cache');
        }

		return $runtime->extensionExists('memcached');
	}
}
'''

### The Result

In normal operation, the Memcached class will use a Registry Runtime instance created specifically for it using it's classname as an instance label.  For testing purposes, we will create mock classes based on the Runtime class which return what we want them to return and pass to the constructor when we create Memcached objects to test.  For use in an application where the developer wishes to have a single runtime registry for all classes, then either it must be passed in through the constructor for all classes, or for classes with the public static variable $runtime defined - it can be set directly there.