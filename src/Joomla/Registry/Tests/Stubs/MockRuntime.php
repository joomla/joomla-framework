<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Tests;

require_once __DIR__ . '/../../Runtime.php';
use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Runtime;
use Joomla\Test\TestHelper;
use Joomla\Registry\Registry;

/**
 * A Registry class to be used  for testing
 *
 * @since  1.1
 */
class MockRuntime extends Runtime
{
	/**
	 * Registry instances container.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $instances = array();

	/**
	 * return
	 *   The value to return for all true/false tests
	 *
	 * @var    boolean
	 * @since  1.1
	 */
	public $objectReturn;

	/**
	 * return
	 *   The value to return for all true/false tests
	 *
	 * @var    boolean
	 * @since  1.1
	 */
	static public $staticReturn;

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
			static::$instances[$id] = new MockRuntime;
		}

		return static::$instances[$id];
	}

	/**
	 * setReturn sets the return value for future calls
	 *
	 * @param   boolean  $return  Boolean value to return for all Runtime methods.
	 *
	 * @return  boolean
	 *
	 * @since   1.1
	 */
	public function setReturn($return)
	{
		if (is_bool($return))
		{
			$this->objectReturn = $return;
			static::$staticReturn = $return;

			return true;
		}

		return false;
	}

	/**
	 * Loads the runtime environment cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 * @return  boolean
	 *
	 * @since   1.1
	 */
	static public function loadRuntime($reload = true, $useCache = true)
	{
		return static::$staticReturn;
	}

	/**
	 * Loads the runtime function cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime functions
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 * @return  boolean  True if cache is enabled and data was refreshed or if cache is disabled
	 *
	 * @since   1.1
	 */
	static public function loadFunctions($reload = true, $useCache = true)
	{
		return static::$staticReturn;
	}

	/**
	 * Loads the runtime class cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime functions
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 * @return  boolean  True if cache is enabled and data was refreshed or if cache is disabled
	 *
	 * @since   1.1
	 */
	static public function loadClasses($reload = true, $useCache = true)
	{
		return static::$staticReturn;
	}

	/**
	 * Loads the runtime extension cache
	 *
	 * @param   boolean  $reload    Whether to force reloading of runtime functions
	 * @param   boolean  $useCache  Whether the extension cache is used
	 *
	 * @return  boolean  True if cache is enabled and data was refreshed or if cache is disabled
	 *
	 * @since   1.1
	 */
	static public function loadExtensions($reload = true, $useCache = true)
	{
		return static::$staticReturn;
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
		return static::$staticReturn;
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
		return static::$staticReturn;
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
		return $this->objectReturn;
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
		return static::$staticReturn;
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
		return static::$staticReturn;
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
		return $this->objectReturn;
	}

	/**
	 * Check if a PHP class exists at this moment
	 *
	 * @param   string   $path      Name of class to check for
	 * @param   boolean  $autoload  Whether class autoloaders should be called
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkClass($path, $autoload = true)
	{
		return static::$staticReturn;
	}

	/**
	 * Check if a PHP function is listed in the cache
	 *
	 * @param   string   $path      Name of class to check for
	 * @param   boolean  $autoload  If class_exists is called, should autoloaders be enabled
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	static public function checkClassCache($path, $autoload = false)
	{
		return static::$staticReturn;
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
		return $this->objectReturn;
	}
}
