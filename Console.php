<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console;

use Joomla\Application\AbstractCliApplication;
use Joomla\Application\Cli\CliOutput;
use Joomla\Application\Cli\ColorStyle;
use Joomla\Application\Cli\Output;
use Joomla\Console\Command\Command;
use Joomla\Console\Command\ListCommand;
use Joomla\Console\Option\Option;
use Joomla\Input;
use Joomla\Registry\Registry;

/**
 * Class Console
 *
 * @since  1.0
 */
class Console extends AbstractCliApplication
{
	/**
	 * The Console title.
	 *
	 * @var  string
	 */
	protected $name = 'Joomla! Console';

	/**
	 * Version of this application.
	 *
	 * @var string
	 */
	protected $version = '1.0';

	/**
	 * Console description.
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * A default command to run as application.
	 *
	 * @var  Command
	 */
	protected $defaultCommand;

	/**
	 * True to set this app auto exit.
	 *
	 * @var boolean
	 */
	protected $autoExit;

	/**
	 * Class constructor.
	 *
	 * @param   Input\Cli  $input   An optional argument to provide dependency injection for the application's
	 *                              input object.  If the argument is a InputCli object that object will become
	 *                              the application's input object, otherwise a default input object is created.
	 *
	 * @param   Registry   $config  An optional argument to provide dependency injection for the application's
	 *                              config object.  If the argument is a Registry object that object will become
	 *                              the application's config object, otherwise a default config object is created.
	 *
	 * @param   CliOutput  $output  The output handler.
	 *
	 * @since   1.0
	 */
	public function __construct(Input\Cli $input = null, Registry $config = null, CliOutput $output = null)
	{
		parent::__construct($input, $config, $output);

		$this->registerDefaultCommand();
	}

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
		$command = $this->getDefaultCommand();

		if (!$command->getCode() && !count($this->input->args))
		{
			array_unshift($this->input->args, 'list');
		}

		try
		{
			/*
			 * Exit code is the Linux/Unix command/shell return code to see
			 * whether this script executed is successful or not.
			 *
			 * @see  http://tldp.org/LDP/abs/html/exitcodes.html
			 */
			$exitCode = $command->execute();
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
	 * registerDefaultCommand
	 *
	 * @return $this
	 */
	public function registerDefaultCommand()
	{
		/** @var Input\Cli $input */
		$input = $this->input;

		$command = with(new Command('default', $input, $this->output))
			->setApplication($this)
			->setDescription('The default application command')
			->addOption(
				array('h', 'help'),
				0,
				'Display this help message.',
				Option::IS_GLOBAL
			)
			->addOption(
				array('q', 'quiet'),
				0,
				'Do not output any message.',
				Option::IS_GLOBAL
			);

		$command->addArgument(
			with(new ListCommand('list', $input, $this->output))
				->setApplication($this)
		);

		$this->defaultCommand = $command;

		return $this;
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
		$this->getDefaultCommand()->addArgument($command);

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

	/**
	 * @return Command
	 */
	public function getDefaultCommand()
	{
		return $this->defaultCommand;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * setCode
	 *
	 * @param   \Closure  $closure
	 *
	 * @return $this
	 */
	public function setCode(\Closure $closure)
	{
		$this->getDefaultCommand()->setCode($closure);

		return $this;
	}
}
