<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application;

use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Joomla Framework Base Application Class
 *
 * @since  1.0
 */
abstract class Base implements LoggerAwareInterface
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
	 * @var    Input
	 * @since  1.0
	 */
	public $input = null;

	/**
	 * A logger.
	 *
	 * @var    LoggerInterface
	 * @since  1.0
	 */
	private $logger;

	/**
	 * Class constructor.
	 *
	 * @param   Input     $input   An optional argument to provide dependency injection for the application's
	 *                             input object.  If the argument is a InputCli object that object will become
	 *                             the application's input object, otherwise a default input object is created.
	 * @param   Registry  $config  An optional argument to provide dependency injection for the application's
	 *                             config object.  If the argument is a Registry object that object will become
	 *                             the application's config object, otherwise a default config object is created.
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input = null, Registry $config = null)
	{
		$this->input = $input instanceof Input ? $input : new Input;
		$this->config = $config instanceof Registry ? $config : new Registry;

		$this->initialise();
	}

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
	 * Get the logger.
	 *
	 * @return  LoggerInterface
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getLogger()
	{
		if ($this->hasLogger())
		{
			return $this->logger;
		}

		throw new \UnexpectedValueException('Logger not set in ' . __CLASS__);
	}

	/**
	 * Checks if a logger is available.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function hasLogger()
	{
		return ($this->logger instanceof LoggerInterface);
	}

	/**
	 * Custom initialisation method.
	 *
	 * Called at the end of the Base::__construct method. This is for developers to inject initialisation code for their application classes.
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 * @since   1.0
	 */
	protected function initialise()
	{
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

	/**
	 * Sets the configuration for the application.
	 *
	 * @param   Registry  $config  A registry object holding the configuration.
	 *
	 * @return  Base  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function setConfiguration(Registry $config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Set the logger.
	 *
	 * @param   LoggerInterface  $logger  The logger.
	 *
	 * @return  Base  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;

		return $this;
	}
}
