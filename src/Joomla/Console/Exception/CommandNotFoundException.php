<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Exception;

use Joomla\Console\Command\AbstractCommand;

/**
 * Command not found exception.
 *
 * @since  1.0
 */
class CommandNotFoundException extends \RuntimeException
{
	/**
	 * Current command to provide information for debug.
	 *
	 * @var AbstractCommand
	 *
	 * @since  1.0
	 *
	 */
	protected $command;

	/**
	 * The last argument to auto complete.
	 *
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $argument;

	/**
	 * Exception constructor.
	 *
	 * @param   string           $message   The Exception message to throw.
	 * @param   AbstractCommand  $command   Current command to provide information for debug.
	 * @param   string           $argument  The last argument to auto complete.
	 *
	 * @since  1.0
	 */
	public function __construct($message, AbstractCommand $command, $argument)
	{
		$this->command  = $command;
		$this->argument = $argument;

		parent::__construct($message, 2);
	}

	/**
	 * Argument setter.
	 *
	 * @param   string  $argument  The last argument to auto complete.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setArgument($argument)
	{
		$this->argument = $argument;
	}

	/**
	 * Argument getter.
	 *
	 * @return string
	 *
	 * @since  1.0
	 */
	public function getChild()
	{
		return $this->argument;
	}

	/**
	 * Command setter.
	 *
	 * @param   AbstractCommand  $command  Current command to provide information for debug.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setCommand($command)
	{
		$this->command = $command;
	}

	/**
	 * Command getter.
	 *
	 * @return AbstractCommand  $command  Current command to provide information for debug.
	 *
	 * @since  1.0
	 */
	public function getCommand()
	{
		return $this->command;
	}
}
