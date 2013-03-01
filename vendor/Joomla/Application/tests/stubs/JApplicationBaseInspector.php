<?php
/**
 * @package     JoomlaFrameworkTests
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Inspector for the JApplicationBase class.
 *
 * @package     JoomlaFrameworkTests
 * @subpackage  Application
 * @since       12.1
 */
class JApplicationBaseInspector extends Joomla\Application\Base
{
	/**
	 * The exit code if the application was closed otherwise null.
	 *
	 * @var     integer
	 * @since   11.3
	 */
	public $closed;

	/**
	 * Mimic exiting the application.
	 *
	 * @param   integer  $code  The exit code (optional; default is 0).
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	public function close($code = 0)
	{
		$this->closed = $code;
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
	 * @since   13.1
	 */
	public function fetchConfigurationData($file = '', $class = 'JConfig')
	{
		return '';
	}

	/**
	 * Method to run the application routines.  Most likely you will want to instantiate a controller
	 * and execute it, or perform some sort of task directly.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	protected function doExecute()
	{
		return;
	}
}
