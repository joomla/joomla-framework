<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry;

use Joomla\Utilities\ArrayHelper;

/**
 * A Registry class to be used to research the current runtime environment
 *
 * @since  1.1
 */
class Runtime extends Registry
{

	/**
	 * Registry instances container.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $instances = array();

	/**
	 * Options
	 *   Options for this Registry Runtime instance
	 *
	 * @var    Registry
	 * @since  1.1
	 */
	protected $options = false;

	/**
	 * useCache
	 *   A flag to indicate if this object should use an internal cache for lookups.
	 *
	 * @var    boolean
	 * @since  1.1
	 */
	 public $useCache = false;

	/**
	 * autoloadClasses
	 *   A flag to indicate if class autoloaders should be invoked
	 *
	 * @var    boolean
	 * @since  1.1
	 */
	public $autoloadClasses = false;

	/**
	 * phpUserFunctions
	 *   A cache of currently defined PHP User functions
	 *
	 * @var    array
	 * @since  1.1
	 */
	static protected $phpUserFunctions = false;

	/**
	 * phpInternalFunctions
	 *   A cache of currently defined PHP Internal functions
	 *
	 * @var    array
	 * @since  1.1
	 */
	static protected $phpInternalFunctions = false;

	/**
	 * phpClasses
	 *   A list of currently defined PHP Classes
	 *
	 * @var    array
	 * @since  1.1
	 */
	static protected $phpClasses = false;

	/**
	 * phpExtensions
	 *   A list of current PHP Extensions
	 *
	 * @var    array
	 * @since  1.1
	 */
	static protected $phpExtensions = false;


	/**
	 * Constructor
	 *
	 * @param   mixed  $data  The data to bind to the new Registry object.
	 * @param   boolean  $load  Whether to initialize the runtime registry.
	 * @param   Registry  $options  A list of options for the runtime registry.
	 * Registry contents of joomla.registry.runtime.key will be set as properties.
	 *
	 * @since   1.1
	 */
	public function __construct($data = null, $load = true, $options = false)
	{

		// Load the options
		if (!$options)
		{
			$options = Registry::getInstance('joomla');
		}
		else
		{
			$options = new Registry($options);
		}

		// Set this objects properties from this objects registry configuration
		$myRegistryKey = str_replace('\\', '.', __CLASS__);
		$myProperties = get_object_vars($this);
		foreach ($myProperties as $property => $value)
		{
			$propertyKey = $myRegistryKey . '.' . $property;
			if ($options->exists($propertyKey))
			{
				$this->$property = $options->get($propertyKey);
			}
		}

		// Instantiate the internal data object.
		if (!isset($this->data))
		{
			parent::__construct($data);
		}

		// Load full runtime environment
		static::loadRuntime($load, $this->useCache);

	}


	/**
	 * Returns a reference to a global Registry object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * <pre>$registry = Registry::getInstance($id);</pre>
	 *
	 * @param   string  $id  An ID for the registry instance
	 *
	 * @return  Registry  The Registry object.
	 *
	 * @since   1.0
	 */
	public static function getInstance($id)
	{
		if (empty(static::$instances[$id]))
		{
			static::$instances[$id] = new Runtime;
		}

		return static::$instances[$id];
	}


	/**
	 * Loads the runtime environment cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 *
	 * @return  boolean  True if cache is enabled and some data was refreshed or if cache is disabled
	 * @since   1.1
	 */
	static public function loadRuntime($reload = true, $useCache = true)
	{
		if (!$useCache)
		{
			return true;
		}
		$loadedFunctions = static::loadFunctions($reload, $useCache);
		$loadedClasses = static::loadClasses($reload, $useCache);
		$loadedExtensions = static::loadExtensions($reload, $useCache);
		$loaded = $loadedFunctions || $loadedClasses || $loadedExtensions;
		return $loaded;
	}


	/**
	 * Loads the runtime function cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime functions
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 *
	 * @return  boolean  True if cache is enabled and data was refreshed or if cache is disabled
	 * @since   1.1
	 */
	static public function loadFunctions($reload = true, $useCache = true)
	{
		if (!$useCache)
		{
			return true;
		}
		// Load the functions if force reload is true or they have not been loaded
		if ($reload ||
			isset(static::$phpInternalFunctions))
		{
			$functions = get_defined_functions();
			static::$phpInternalFunctions = asort($functions['internal']);
			static::$phpUserFunctions = asort($functions['user']);
			return true;
		}

		return false;
	}

	/**
	 * Loads the runtime class cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime functions
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 *
	 * @return  boolean  True if cache is enabled and data was refreshed or if cache is disabled
	 * @since   1.1
	 */
	static public function loadClasses($reload = true, $useCache = true)
	{
		if (!$useCache)
		{
			return true;
		}
		// Load the functions if force reload is true or they have not been loaded
		if ($reload ||
			isset(static::$phpClasses))
		{
			$classes = get_declared_classes();
			static::$phpClasses = asort($classes);
			return true;
		}

		return false;
	}

	/**
	 * Loads the runtime extension cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime functions
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 * @return  boolean  True if cache is enabled and data was refreshed or if cache is disabled
	 * @since   1.1
	 */
	static public function loadExtensions($reload = true, $useCache = true)
	{
		if (!$useCache)
		{
			return true;
		}
		// Load the functions if force reload is true or they have not been loaded
		if ($reload ||
			isset(static::$phpExtensions))
		{
			$extensions = get_loaded_extensions();
			static::$phpExtensions = asort($extensions);
			return true;
		}
		return false;
	}

	/**
	 * Check if a PHP extension is loaded at this moment
	 *
	 * @param   string  $path  Name of extension to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkExtension($path)
	{
		return extension_loaded($path);
	}

	/**
	 * Check if a PHP extension is listed in the cache
	 *
	 * @param   string  $path  Name of extension to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkExtensionCache($path)
	{
		// Make sure extension cache is loaded
		$loaded = static::loadExtensions(false, true);
		$exists =  in_array($path, static::$phpExtensions);
		// If extension cache was not reloaded here, make sure it does not really exist
		if (!$exists && !$loaded)
		{
			$exists = static::checkExtension($path);
			// Reload extension cache on change
			if ($exists)
			{
				$reloaded = static::loadExtensions(true, true);
			}
		}

		return $exists;
	}


	/**
	 * Check if a PHP extension is loaded
	 *
	 * @param   string  $path  Registry path (e.g. joomla.content.showauthor)
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function extensionExists($path)
	{
		if ($this->useCache)
		{
			return static::checkExtensionCache($path);
		}
		else
		{
			return static::checkExtension($path);
		}
	}


	/**
	 * Check if a PHP function is defined at this moment
	 *
	 * @param   string  $path  Name of function to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkFunction($path)
	{
		return function_exists($path);
	}

	/**
	 * Check if a PHP function is listed in the cache
	 *
	 * @param   string  $path  Name of extension to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkFunctionCache($path)
	{

		// Make sure extension cache is loaded
		$loaded = static::loadFunctions(false, true);
		$exists = in_array($path, static::$phpUserFunctions);
		if (!$exists)
		{
			$exists = in_array($path, static::$phpInternalFunctions);
		}
		// If extension cache was not reloaded here, make sure it does not really exist
		if (!$exists && !$loaded)
		{
			$exists = static::checkFunction($path);
			// Reload extension cache on change
			if ($exists)
			{
				$reloaded = static::loadFunctions(true, true);
			}
		}
		return $exists;
	}


	/**
	 * Check if a PHP Function is defined
	 *
	 * @param   string  $path  Name of function to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function functionExists($path)
	{
		if ($this->useCache)
		{
			return static::checkFunctionCache($path);
		}
		else
		{
			return static::checkFunction($path);
		}
	}


	/**
	 * Check if a PHP class exists at this moment
	 *
	 * @param   string  $path  Name of class to check for
	 * @param   boolean  $autoload  Whether class autoloaders should be called
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkClass($path, $autoload = true)
	{
		return class_exists($path, $autoload);
	}

	/**
	 * Check if a PHP function is listed in the cache
	 *
	 * @param   string  $path  Name of class to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkClassCache($path, $autoload = false)
	{
		// Make sure extension cache is loaded
		$loaded = static::loadClasses(false, true);
		$exists = in_array($path, static::$phpClasses);
		// If extension cache was not reloaded here, make sure it does not really exist
		if (!$exists && !$loaded)
		{
			$exists = static::checkClass($path, $autoload);
			// Reload extension cache on change
			if ($exists)
			{
				$reloaded = static::loadClasses(true, true);
			}
		}
		return $exists;
	}


	/**
	 * Check if a PHP function is defined
	 *
	 * @param   string  $path  Name of function to check for
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function classExists($path)
	{
		if ($this->useCache)
		{
			return static::checkClassCache($path, $this->autoloadClasses);
		}
		else
		{
			return static::checkClass($path, $this->autoloadClasses);
		}
	}

	/**
	 * Check if a registry path exists.  Special case logic for joomla.registry.runtime.class,
	 * joomla.registry.runtime.function, and joomla.registry.runtime.class
	 *
	 * @param   string  $path  Registry path (e.g. joomla.content.showauthor)
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function exists($path)
	{

		$myRegistryKey = str_replace('\\', '.', __CLASS__);
		// Special logic to support functions, classes, and extensions
		if (strpos($path, $myRegistryKey) === 0)
		{
			$tmpPath = substr($path,0,strlen($myRegistryKey));
			if (strlen($tmpPath) > 0)
			{
				$nodes = explode('.', $tmpPath);
				$checkFor = array_shift($nodes);
				if ($checkFor == 'function')
				{
					$path = implode('.', $nodes);
					return $this->functionExists($path);
				}
				if ($checkFor == 'extension')
				{
					$path = implode('.', $nodes);
					return $this->functionExists($path);
				}
				if ($checkFor == 'class')
				{
					$path = implode('.', $nodes);
					return $this->functionExists($path);
				}
			}
		}

		return parent::exists($path);
	}
}
