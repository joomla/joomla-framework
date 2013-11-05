<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console;

use Joomla\Application\AbstractCliApplication;
use Joomla\Application\Cli\Output;
use Joomla\Console\Command\Command;
use Joomla\Input;

/**
 * Class Console
 *
 * @since  1.0
 */
class Console extends AbstractCliApplication
{
	/**
	 * Save all commands here.
	 *
	 * @var array
	 */
	public $commands = array();

	/**
	 * True to set this app auto exit.
	 *
	 * @var boolean
	 */
	protected $autoExit;

	/**
	 * Method to run the application routines.
	 *
	 * @return  int  The Unix Console/Shell exit code.
	 *
	 * @see     http://tldp.org/LDP/abs/html/exitcodes.html
	 *
	 * @since   1.0
	 * @throws  \LogicException
	 * @throws  \Exception
	 */
	public function doExecute()
	{
		/** @var $input Input\Cli */
		$input = $this->input;

		if (!count($input->args))
		{
			$input->args[0] = 'help';
		}

		$name = $this->input->args[0];

		if (empty($this->commands[$name]))
		{
			throw new \LogicException(sprintf('Command %s not found', $name));
		}

		try
		{
			/*
			 * Exit code is the Linux/Unix command/shell return code to see
			 * whether this script executed is successful or not.
			 *
			 * @see  http://tldp.org/LDP/abs/html/exitcodes.html
			 */
			$exitCode = $this->commands[$name]->execute();
		}
		catch (\Exception $e)
		{
			// @TODO Write an exception renderer.
			throw $e;
		}

		if ($this->autoExit)
		{
			if ($exitCode > 255 || $exitCode == -1)
			{
				$exitCode = 255;
			}

			exit($exitCode);
		}

		return $exitCode;
	}

	/**
	 * Register a new Console.
	 *
	 * @param   string  $name  The command name.
	 *
	 * @return Command The created commend.
	 */
	public function register($name)
	{
		return $this->addCommand(new Command($name, $this->input));
	}

	/**
	 * Add a new command object.
	 *
	 * If a command with the same name already exists, it will be overridden.
	 *
	 * @param   Command  $command  A Console object
	 *
	 * @return  Command  The registered command
	 */
	public function addCommand(Command $command)
	{
		$command->setApplication($this);

		$this->commands[$command->getName()] = $command;

		return $command;
	}

	/**
	 * Sets whether to automatically exit after a command execution or not.
	 *
	 * @param   boolean  $boolean  Whether to automatically exit after a command execution or not.
	 *
	 * @return  Console  Return this object to support chaining.
	 */
	public function setAutoExit($boolean)
	{
		$this->autoExit = (boolean) $boolean;

		return $this;
	}
}
