<?php
/**
 * Part of the Joomla Framework Test Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Test;

use Joomla\Application\AbstractWebApplication;

/**
 * Inspector for the Joomla\Application\AbstractWebApplication class.
 *
 * @since  1.0
 */
class WebInspector extends AbstractWebApplication
{
	/**
	 * @var     boolean  True to mimic the headers already being sent.
	 * @since   1.0
	 */
	public static $headersSent = false;

	/**
	 * @var     boolean  True to mimic the connection being alive.
	 * @since   1.0
	 */
	public static $connectionAlive = true;

	/**
	 * @var     array  List of sent headers for inspection. array($string, $replace, $code).
	 * @since   1.0
	 */
	public $headers = array();

	/**
	 * @var     integer  The exit code if the application was closed otherwise null.
	 * @since   1.0
	 */
	public $closed;

	/**
	 * Allows public access to protected method.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function checkConnectionAlive()
	{
		return self::$connectionAlive;
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function checkHeadersSent()
	{
		return self::$headersSent;
	}

	/**
	 * Mimic exiting the application.
	 *
	 * @param   integer  $code  The exit code (optional; default is 0).
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function close($code = 0)
	{
		$this->closed = $code;
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function doExecute()
	{
		return;
	}

	/**
	 * Allows public access to protected method.
	 *
	 * @param   string   $string   The header string.
	 * @param   boolean  $replace  The optional replace parameter indicates whether the header should
	 *                             replace a previous similar header, or add a second header of the same type.
	 * @param   integer  $code     Forces the HTTP response code to the specified value. Note that
	 *                             this parameter only has an effect if the string is not empty.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function header($string, $replace = true, $code = null)
	{
		$this->headers[] = array($string, $replace, $code);
	}

	/**
	 * Method to load a PHP configuration class file based on convention and return the instantiated data object.  You
	 * will extend this method in child classes to provide configuration data from whatever data source is relevant
	 * for your specific application.
	 *
	 * @param   string  $file   The path and filename of the configuration file. If not provided, configuration.php
	 *                          in JPATH_ROOT will be used.
	 * @param   string  $class  The class name to instantiate.
	 *
	 * @return  mixed   Either an array or object to be loaded into the configuration object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function fetchConfigurationData($file = '', $class = '\\Joomla\\Test\\TestConfig')
	{
		// Instantiate variables.
		$config = array();

		if (empty($file) && defined('JPATH_ROOT'))
		{
			$file = JPATH_ROOT . '/configuration.php';

			// Applications can choose not to have any configuration data
			// by not implementing this method and not having a config file.
			if (!file_exists($file))
			{
				$file = '';
			}
		}

		if (!empty($file))
		{
			if (is_file($file))
			{
				require_once $file;
			}

			if (class_exists($class))
			{
				$config = new $class;
			}
			else
			{
				throw new \RuntimeException('Configuration class does not exist.');
			}
		}

		return $config;
	}
}
