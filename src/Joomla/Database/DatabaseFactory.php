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
class DatabaseFactory
{
	/**
	 * Contains the current Factory instance
	 *
	 * @var    DatabaseFactory
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
	 * @return  DatabaseDriver  A database driver object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getDriver($name = 'mysqli', $options = array())
	{
		// Sanitize the database connector options.
		$options['driver']   = preg_replace('/[^A-Z0-9_\.-]/i', '', $name);
		$options['database'] = (isset($options['database'])) ? $options['database'] : null;
		$options['select']   = (isset($options['select'])) ? $options['select'] : true;

		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\' . ucfirst(strtolower($options['driver'])) . '\\' . ucfirst(strtolower($options['driver'])) . 'Driver';

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
	 * @param   string          $name  Name of the driver you want an exporter for.
	 * @param   DatabaseDriver  $db    Optional Driver instance
	 *
	 * @return  DatabaseExporter  An exporter object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getExporter($name, DatabaseDriver $db = null)
	{
		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\' . ucfirst(strtolower($name)) . '\\' . ucfirst(strtolower($name)) . 'Exporter';

		// Make sure we have an exporter class for this driver.
		if (!class_exists($class))
		{
			// If it doesn't exist we are at an impasse so throw an exception.
			throw new \RuntimeException('Database Exporter not found.');
		}

		/* @var  $o  DatabaseExporter */
		$o = new $class;

		if ($db instanceof DatabaseDriver)
		{
			$o->setDbo($db);
		}

		return $o;
	}

	/**
	 * Gets an importer class object.
	 *
	 * @param   string          $name  Name of the driver you want an importer for.
	 * @param   DatabaseDriver  $db    Optional Driver instance
	 *
	 * @return  DatabaseImporter  An importer object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getImporter($name, DatabaseDriver $db = null)
	{
		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\' . ucfirst(strtolower($name)) . '\\' . ucfirst(strtolower($name)) . 'Importer';

		// Make sure we have an importer class for this driver.
		if (!class_exists($class))
		{
			// If it doesn't exist we are at an impasse so throw an exception.
			throw new \RuntimeException('Database importer not found.');
		}

		/* @var  $o  DatabaseImporter */
		$o = new $class;

		if ($db instanceof DatabaseDriver)
		{
			$o->setDbo($db);
		}

		return $o;
	}

	/**
	 * Gets an instance of the factory object.
	 *
	 * @return  DatabaseFactory
	 *
	 * @since   1.0
	 */
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::setInstance(new static);
		}

		return self::$instance;
	}

	/**
	 * Get the current query object or a new Query object.
	 *
	 * @param   string          $name  Name of the driver you want an query object for.
	 * @param   DatabaseDriver  $db    Optional Driver instance
	 *
	 * @return  DatabaseQuery  The current query object or a new object extending the Query class.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getQuery($name, DatabaseDriver $db = null)
	{
		// Derive the class name from the driver.
		$class = '\\Joomla\\Database\\' . ucfirst(strtolower($name)) . '\\' . ucfirst(strtolower($name)) . 'Query';

		// Make sure we have a query class for this driver.
		if (!class_exists($class))
		{
			// If it doesn't exist we are at an impasse so throw an exception.
			throw new \RuntimeException('Database Query class not found');
		}

		return new $class($db);
	}

	/**
	 * Gets an instance of a factory object to return on subsequent calls of getInstance.
	 *
	 * @param   DatabaseFactory  $instance  A Factory object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function setInstance(DatabaseFactory $instance = null)
	{
		self::$instance = $instance;
	}
}
