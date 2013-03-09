<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application;


/**
 * Joomla Platform Base Application Class
 *
 * @since    1.0
 */
abstract class Base
{
	/**
	 * The application configuration object.
	 *
	 * @var    Registry
	 * @since  1.0
	 */
	protected $config;

	/**
	 * The application input object.
	 *
	 * @var    Joomla\Input\Input
	 * @since  1.0
	 */
	public $input = null;

	/**
	 * Method to close the application.
	 *
	 * @param   integer  $code  The exit code (optional; default is 0).
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 * @since   1.0
	 */
	public function close($code = 0)
	{
		exit($code);
	}

	/**
	 * Method to run the application routines.  Most likely you will want to instantiate a controller
	 * and execute it, or perform some sort of task directly.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	abstract protected function doExecute();

	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		// @event onBeforeExecute

		// Perform application routines.
		$this->doExecute();

		// @event onAfterExecute
	}

	/**
	 * Method to load a PHP configuration class file based on convention and return the instantiated data object.  You
	 * will extend this method in child classes to provide configuration data from whatever data source is relevant
	 * for your specific application.
	 *
	 * @param   string  $file   The path and filename of the configuration file. If not provided, configuration.php
	 *                          in JPATH_BASE will be used.
	 * @param   string  $class  The class name to instantiate.
	 *
	 * @return  mixed   Either an array or object to be loaded into the configuration object.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	abstract protected function fetchConfigurationData($file = '', $class = 'JConfig');

	/**
	 * Load an object or array into the application configuration object.
	 *
	 * @param   mixed  $data  Either an array or object to be loaded into the configuration object.
	 *
	 * @return  Base  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function loadConfiguration($data)
	{
		// Load the data into the configuration object.
		if (is_array($data))
		{
			$this->config->loadArray($data);
		}
		elseif (is_object($data))
		{
			$this->config->loadObject($data);
		}

		return $this;
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @param   string  $key      The name of the property.
	 * @param   mixed   $default  The default value (optional) if none is set.
	 *
	 * @return  mixed   The value of the configuration.
	 *
	 * @since   1.0
	 */
	public function get($key, $default = null)
	{
		return $this->config->get($key, $default);
	}

	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $key    The name of the property.
	 * @param   mixed   $value  The value of the property to set (optional).
	 *
	 * @return  mixed   Previous value of the property
	 *
	 * @since   1.0
	 */
	public function set($key, $value = null)
	{
		$previous = $this->config->get($key);
		$this->config->set($key, $value);

		return $previous;
	}
}
