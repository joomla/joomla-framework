<?php
/**
 * @package     Joomla\Framework
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application;

use Joomla\Input\Input;
use Joomla\Registry\Registry;
use RuntimeException;

use Joomla\Input\Cli as InputCli;

/**
 * Base class for a Joomla! command line application.
 *
 * @package     Joomla\Framework
 * @subpackage  Application
 * @since       11.4
 */
abstract class Cli extends Base
{
	/**
	 * @var    Cli  The application instance.
	 * @since  11.1
	 */
	protected static $instance;

	/**
	 * Class constructor.
	 *
	 * @param   mixed  $input   An optional argument to provide dependency injection for the application's
	 *                          input object.  If the argument is a InputCli object that object will become
	 *                          the application's input object, otherwise a default input object is created.
	 * @param   mixed  $config  An optional argument to provide dependency injection for the application's
	 *                          config object.  If the argument is a Registry object that object will become
	 *                          the application's config object, otherwise a default config object is created.
	 *
	 * @since   11.1
	 */
	public function __construct(InputCli $input = null, Registry $config = null)
	{
		// Close the application if we are not executed from the command line.
		// @codeCoverageIgnoreStart
		if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv']))
		{
			$this->close();
		}

		// @codeCoverageIgnoreEnd

		// If a input object is given use it.
		if ($input instanceof Input)
		{
			$this->input = $input;
		}
		else
		// Create the input based on the application logic.
		{
			if (class_exists('\\Joomla\\Input\\Cli'))
			{
				$this->input = new InputCLI;
			}
		}

		// If a config object is given use it.
		if ($config instanceof Registry)
		{
			$this->config = $config;
		}
		else
		// Instantiate a new configuration object.
		{
			$this->config = new Registry;
		}

		// Load the configuration object.
		$this->loadConfiguration($this->fetchConfigurationData());

		// Set the execution datetime and timestamp;
		$this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
		$this->set('execution.timestamp', time());

		// Set the current directory.
		$this->set('cwd', getcwd());
	}

	/**
	 * Returns a reference to the global Cli object, only creating it if it doesn't already exist.
	 *
	 * This method must be invoked as: $cli = Cli::getInstance();
	 *
	 * @param   string  $name  The name (optional) of the Cli class to instantiate.
	 *
	 * @return  Cli
	 *
	 * @since   11.1
	 * @throws  RuntimeException
	 */
	public static function getInstance($name = null)
	{
		// Only create the object if it doesn't exist.
		if (empty(self::$instance))
		{
			if (class_exists($name) && (is_subclass_of($name, __CLASS__)))
			{
				self::$instance = new $name;
			}
			else
			{
				throw new RuntimeException(sprintf('Could not instantiate %s as an instance of %s.', $name, __CLASS__));
			}
		}

		return self::$instance;
	}

	/**
	 * Write a string to standard output.
	 *
	 * @param   string   $text  The text to display.
	 * @param   boolean  $nl    True (default) to append a new line at the end of the output string.
	 *
	 * @return  Cli  Instance of $this to allow chaining.
	 *
	 * @codeCoverageIgnore
	 * @since   11.1
	 */
	public function out($text = '', $nl = true)
	{
		fwrite(STDOUT, $text . ($nl ? "\n" : null));

		return $this;
	}

	/**
	 * Get a value from standard input.
	 *
	 * @return  string  The input string from standard input.
	 *
	 * @codeCoverageIgnore
	 * @since   11.1
	 */
	public function in()
	{
		return rtrim(fread(STDIN, 8192), "\n");
	}
}
