<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database;

/**
 * Joomla Framework Database Factory class
 *
 * @since  1.0
 */
class Factory
{
	/**
	 * Contains the current Factory instance
	 *
	 * @var    Factroy
	 * @since  1.0
	 */
	private static $instance = null;

	/**
	 * Method to return a Driver instance based on the given options. There are three global options and then
	 * the rest are specific to the database driver. The 'database' option determines which database is to
	 * be used for the connection. The 'select' option determines whether the connector should automatically select
	 * the chosen database.
	 *
	 * Instances are unique to the given options and new objects are only created when a unique options array is
	 * passed into the method.  This ensures that we don't end up with unnecessary database connection resources.
	 *
	 * @param   string  $name     Name of the database driver you'd like to instantiate
	 * @param   array   $options  Parameters to be passed to the database driver.
	 *
	 * @return  Driver  A database driver object.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function getDriver($name = 'mysqli', $options = array())
	{
		// Sanitize the database connector options.
		$options['driver']   = preg_replace('/[^A-Z0-9_\.-]/i', '', $name);
		$options['database'] = (isset($options['database'])) ? $options['database'] : null;
		$options['select']   = (isset($options['select'])) ? $options['select'] : true;

		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\Driver\\' . ucfirst(strtolower($options['driver']));

		// If the class still doesn't exist we have nothing left to do but throw an exception.  We did our best.
		if (!class_exists($class))
		{
			throw new \RuntimeException(sprintf('Unable to load Database Driver: %s', $options['driver']));
		}

		// Create our new Driver connector based on the options given.
		try
		{
			$instance = new $class($options);
		}
		catch (\RuntimeException $e)
		{
			throw new \RuntimeException(sprintf('Unable to connect to the Database: %s', $e->getMessage()));
		}

		return $instance;
	}

	/**
	 * Gets an exporter class object.
	 *
	 * @param   string  $name  Name of the driver you want an exporter for.
	 * @param   Driver  $db    Optional Driver instance
	 *
	 * @return  Exporter  An exporter object.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function getExporter($name, Driver $db = null)
	{
		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\Exporter\\' . ucfirst(strtolower($name));

		// Make sure we have an exporter class for this driver.
		if (!class_exists($class))
		{
			// If it doesn't exist we are at an impasse so throw an exception.
			throw new \RuntimeException('Database Exporter not found.');
		}

		$o = new $class;

		if ($db instanceof Driver)
		{
			$o->setDbo($db);
		}

		return $o;
	}

	/**
	 * Gets an importer class object.
	 *
	 * @param   string  $name  Name of the driver you want an importer for.
	 * @param   Driver  $db    Optional Driver instance
	 *
	 * @return  Importer  An importer object.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function getImporter($name, Driver $db = null)
	{
		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\Importer\\' . ucfirst(strtolower($name));

		// Make sure we have an importer class for this driver.
		if (!class_exists($class))
		{
			// If it doesn't exist we are at an impasse so throw an exception.
			throw new \RuntimeException('Database importer not found.');
		}

		$o = new $class;

		if ($db instanceof Driver)
		{
			$o->setDbo($db);
		}

		return $o;
	}

	/**
	 * Gets an instance of the factory object.
	 *
	 * @return  Factory
	 *
	 * @since   1.0
	 */
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::setInstance();
		}

		return self::$instance;
	}

	/**
	 * Get the current query object or a new Query object.
	 *
	 * @param   string  $name  Name of the driver you want an query object for.
	 * @param   Driver  $db    Optional Driver instance
	 *
	 * @return  Query  The current query object or a new object extending the Query class.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function getQuery($name, Driver $db = null)
	{
		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\Query\\' . ucfirst(strtolower($name));

		// Make sure we have a query class for this driver.
		if (!class_exists($class))
		{
			// If it doesn't exist we are at an impasse so throw an exception.
			throw new RuntimeException('Database Query class not found');
		}

		return new $class($db);
	}

	/**
	 * Gets an instance of a factory object to return on subsequent calls of getInstance.
	 *
	 * @param   Factory  $instance  A Factory object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function setInstance(Factory $instance = null)
	{
		self::$instance = $instance;
	}
}
