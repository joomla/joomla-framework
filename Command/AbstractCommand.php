<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Command;

use Joomla\Application\AbstractCliApplication;
use Joomla\Application\Cli\Output\Stdout;
use Joomla\Application\Cli\CliOutput;
use Joomla\Console\Option\Option;
use Joomla\Input;

/**
 * Abstract Console class.
 *
 * @since  1.0
 */
abstract class AbstractCommand
{
	/**
	 * @var  AbstractCliApplication
	 */
	public $application;

	/**
	 * The Cli input object.
	 *
	 * @var Input\Cli
	 */
	protected $input;

	/**
	 * The cli output object.
	 *
	 * @var CliOutput
	 */
	protected $output;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name;

	/**
	 * The Arguments(SubCommends) storage.
	 *
	 * @var array
	 */
	protected $arguments;

	/**
	 * The Options storage.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Global Options.
	 *
	 * @var array
	 */
	protected $globalOptions = array();

	/**
	 * Alias of options.
	 *
	 * @var array
	 */
	protected $optionAlias = array(
		'global' => array(),
		'normal' => array()
	);

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description;

	/**
	 * The manual about this command.
	 *
	 * @var  string
	 */
	protected $help;

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = '%s <cmd><command></cmd> <option>[option]</option>';

	/**
	 * The closure to execute.
	 *
	 * @var  \Closure
	 */
	protected $code;

	/**
	 * The parent Console if this is a sub comment.
	 *
	 * @var AbstractCommand
	 */
	protected $parent;

	/**
	 * Console constructor.
	 *
	 * @param   string           $name    Console name.
	 * @param   Input\Cli        $input   Cli input object.
	 * @param   CliOutput        $output  Cli output object.
	 * @param   AbstractCommand  $parent  Parent Console.
	 *
	 * @throws \LogicException
	 */
	public function __construct($name = null, Input\Cli $input = null, CliOutput $output = null, AbstractCommand $parent = null)
	{
		$this->name   = $name   ?: $this->name;
		$this->input  = $input  ?: new Input\Cli;
		$this->output = $output ?: new Stdout;
		$this->parent = $parent;

		$this->configure();

		if (!$this->name)
		{
			throw new \LogicException('Console name can not be empty.');
		}
	}

	/**
	 * Execute this command.
	 *
	 * @return  mixed  Executed result or exit code.
	 */
	public function execute()
	{
		if (count($this->arguments) && count($this->input->args))
		{
			$name = $this->input->args[0];

			try
			{
				return $this->executeSubCommand($name);
			}
			catch (\InvalidArgumentException $e)
			{
				$this->renderAlternatives($name, $e);

				return 2;
			}
			catch (\Exception $e)
			{
				$this->renderException($e);

				return 2;
			}
		}

		if ($this->code)
		{
			if ($this->code instanceof \Closure)
			{
				$code = $this->code;

				return $code($this, $this->input, $this->output);
			}
			elseif (is_callable($this->code))
			{
				return call_user_func_array($this->code, array($this, $this->input, $this->output));
			}
		}

		return $this->doExecute();
	}

	/**
	 * Execute this command.
	 *
	 * @throws \LogicException
	 *
	 * @return void
	 */
	protected function doExecute()
	{
		throw new \LogicException('You must override the doExecute() method in the concrete command class.');
	}

	/**
	 * Configure command.
	 *
	 * @return void
	 */
	protected function configure()
	{
	}

	/**
	 * Execute the sub command.
	 *
	 * @param   string     $name    The command name.
	 * @param   Input\Cli  $input   The Cli Input object.
	 * @param   CliOutput  $output  The Cli output object.
	 *
	 * @throws  \LogicException
	 * @return  mixed
	 */
	protected function executeSubCommand($name, Input\Cli $input = null, CliOutput $output = null)
	{
		if (empty($this->arguments[$name]))
		{
			throw new \InvalidArgumentException(sprintf('Command "%s" not found.', $name));
		}

		/** @var $subCommand AbstractCommand */
		$subCommand = $this->arguments[$name];

		// Remove first argument and send it to child
		if (!$input)
		{
			$input = $this->input;

			array_shift($input->args);
		}

		$subCommand->setInput($input);

		if (!$output)
		{
			$output = $this->output;
		}

		$subCommand->setOutput($output);

		$subCommand->setParent($this);

		return $subCommand->execute();
	}

	/**
	 * Input setter.
	 *
	 * @param   Input\Cli  $input  The Cli Input object.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setInput(Input\Cli $input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Output setter.
	 *
	 * @param   CliOutput  $output  The Cli Output object.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setOutput(CliOutput $output)
	{
		$this->output = $output;

		return $this;
	}

	/**
	 * Parent command setter.
	 *
	 * @param   AbstractCommand  $parent  The parent comment.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setParent(AbstractCommand $parent)
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Get Parent Command.
	 *
	 * @return  AbstractCommand
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Add an argument(sub command) setting.
	 *
	 * @param   string|AbstractCommand  $argument     The argument name or Console object.
	 *                                                If we just send a string, the object will auto create.
	 * @param   null                    $description  Console description.
	 * @param   array                   $options      Console options.
	 * @param   \Closure                $code         The closure to execute.
	 *
	 * @return   Command  Return this object to support chaining.
	 */
	public function addArgument($argument, $description = null, $options = array(), \Closure $code = null)
	{
		if (!($argument instanceof AbstractCommand))
		{
			$argument = new static($argument, $this->input, $this->output, $this);
		}

		// Set argument detail
		$argument->setApplication($this->application)
			->setInput($this->input);

		if ($description !== null)
		{
			$argument->setDescription($description);
		}

		if (count($options))
		{
			$argument->setOptions($options);
			$argument->setOptions($options);
		}

		if ($code)
		{
			$argument->setCode($code);
		}

		// Set global options to sub command
		/** @var $option Option */
		foreach ($this->globalOptions as $option)
		{
			$argument->addOption($option);

			$alias  = $option->getAlias();
			$global = $option->isGlobal();

			foreach ($alias as $var)
			{
				$argument->setOptionAlias($option->getName(), $var, $global);
			}
		}

		$name  = $argument->getName();

		$this->arguments[$name] = $argument;

		return $this;
	}

	/**
	 * Get a argument(command) by name.
	 *
	 * @param   string  $name  Argument name.
	 *
	 * @return Command|null  Return command or null.
	 */
	public function getArgument($name)
	{
		if (!empty($this->arguments[$name]))
		{
			return $this->arguments[$name];
		}

		return null;
	}

	/**
	 * Get arguments array.
	 *
	 * @return array  Arguments.
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * Batch set arguments (sub commands).
	 *
	 * @param   array  $arguments  An array include argument objects.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setArguments($arguments)
	{
		$arguments = (array) $arguments;

		foreach ($arguments as $argument)
		{
			$this->addArgument($argument);
		}

		return $this;
	}

	/**
	 * Add a option object to this command.
	 *
	 * @param   mixed   $option       The option name. Can be a string, an array or an object.
	 *                                 If we use array, the first element will be option name, others will be alias.
	 * @param   mixed   $default      The default value when we get a non-exists option.
	 * @param   string  $description  The option description.
	 * @param   bool    $global       If true, this option will be a global option that sub commends will extends it.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function addOption($option, $default = null, $description = null, $global = false)
	{
		if (!($option instanceof Option))
		{
			$option = new Option($option, $default, $description, $global);
		}

		$option->setInput($this->input);

		$name   = $option->getName();
		$alias  = $option->getAlias();
		$global = $option->isGlobal();

		if ($global)
		{
			$this->globalOptions[$name] = $option;

			// Global option should not equal to normal option
			unset($this->options[$name]);
		}
		else
		{
			$this->options[$name] = $option;

			// Global option should not equal to normal option
			unset($this->globalOptions[$name]);
		}

		$this->setOptionAlias($name, $alias, $global);

		return $this;
	}

	/**
	 * Get value from an option.
	 *
	 * If the name not found, we use alias to find options.
	 *
	 * @param   string  $name     The option name.
	 * @param   string  $default  The default value when option not setted.
	 *
	 * @return  mixed  The option value we want to get or default value if option not exists.
	 */
	public function getOption($name, $default = null)
	{
		$options = $this->options;

		// Get from normal
		if (empty($this->options[$name]))
		{
			// Get from normal alias
			if (!empty($this->optionAlias['normal'][$name]))
			{
				$name = $this->optionAlias['normal'][$name];
			}
			// Get from global
			else
			{
				$options = $this->globalOptions;

				// Get from global alias
				if (!empty($this->optionAlias['global'][$name]))
				{
					$name = $this->optionAlias['global'][$name];
				}
			}
		}

		if (empty($options[$name]))
		{
			return $default;
		}

		$option = $options[$name];

		if ($option instanceof Option)
		{
			return $option->getValue();
		}
		else
		{
			return $default;
		}
	}

	/**
	 * Get options.
	 *
	 * @return mixed  The options array.
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Get all options include global.
	 *
	 * @return array  The options array.
	 */
	public function getAllOptions()
	{
		return array_merge($this->globalOptions, $this->options);
	}

	/**
	 * Batch add options to command.
	 *
	 * @param   mixed  $options  An options array.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setOptions($options)
	{
		$options = is_array($options) ? $options : array($options);

		foreach ($options as $option)
		{
			$this->addOption($option);
		}

		return $this;
	}

	/**
	 * The command description getter.
	 *
	 * @return string  Console description.
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * The command description setter.
	 *
	 * @param   string  $description  Console description.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Console name getter.
	 *
	 * @return string  Console name.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Console name setter.
	 *
	 * @param   string  $name  Console name.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Console execute code getter.
	 *
	 * @return  \Closure  Console execute code.
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Console execute code setter.
	 *
	 * @param   \Closure  $code  Console execute code.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setCode(\Closure $code = null)
	{
		if ($code)
		{
			$this->code = $code;
		}

		return $this;
	}

	/**
	 * Get the options alias.
	 *
	 * @return  array  The option alias.
	 */
	public function getOptionAlias()
	{
		return $this->optionAlias;
	}

	/**
	 * Sets the option alias.
	 *
	 * @param   string  $name    The option name.
	 * @param   string  $alias   The alias to map this option.
	 * @param   bool    $global  Is global option?
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setOptionAlias($name, $alias, $global = false)
	{
		$subKey = $global ? 'global' : 'normal';

		$alias = (array) $alias;

		foreach ($alias as $var)
		{
			$this->optionAlias[$subKey][$var] = $name;
		}

		return $this;
	}

	/**
	 * Get the application.
	 *
	 * @return AbstractCliApplication
	 */
	public function getApplication()
	{
		return $this->application;
	}

	/**
	 * Set the application.
	 *
	 * @param   AbstractCliApplication  $application  Application object.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setApplication($application)
	{
		$this->application = $application;

		return $this;
	}

	/**
	 * Get the help manual.
	 *
	 * @return string
	 */
	public function getHelp()
	{
		return $this->help;
	}

	/**
	 * Sets the help manual
	 *
	 * @param   string  $help  The help manual.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setHelp($help)
	{
		$this->help = $help;

		return $this;
	}

	/**
	 * Get the usage.
	 *
	 * @return string
	 */
	public function getUsage()
	{
		return sprintf($this->usage, $this->getName());
	}

	/**
	 * Sets the usage to tell user how to use this command.
	 *
	 * @param   string  $usage  Usage of this command.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 */
	public function setUsage($usage)
	{
		$this->usage = $usage;

		return $this;
	}

	/**
	 * Render auto complete alternatives.
	 *
	 * @param   string                     $name       The command name to auto completed.
	 * @param   \InvalidArgumentException  $exception  The exception of wrong argument.
	 *
	 * @return  void
	 */
	public function renderAlternatives($name, $exception)
	{
		/** @var $exception \InvalidArgumentException */
		$message      = $exception->getMessage();
		$autoComplete = '';
		$alternatives = array();

		// Autocomplete
		foreach ($this->arguments as $command)
		{
			/** @var $command Command */
			$commandName = $command->getName();

			$lev = levenshtein($name, $commandName);

			if ($lev <= strlen($name) / 3 || false !== strpos($commandName, $name))
			{
				$alternatives[] = "    " . $commandName;
			}
		}

		if (count($alternatives))
		{
			$autoComplete = "Did you mean one of this?\n";
			$autoComplete .= implode($alternatives);
		}

		$this->out('');
		$this->out("<error>{$message}</error>");
		$this->out('');
		$this->out($autoComplete);
	}

	/**
	 * Render exception for debugging.
	 *
	 * @param   \Exception  $exception  The exception we want to render.
	 *
	 * @return void
	 */
	public function renderException($exception)
	{
		/** @var $exception \Exception */
		$class = get_class($exception);

		$output = <<<EOF
<error>Exception '{$class}' with message:</error> <fg=cyan;options=bold>{$exception->getMessage()}</fg=cyan;options=bold>
<info>in {$exception->getFile()}:{$exception->getLine()}</info>

<error>Stack trace:</error>
{$exception->getTraceAsString()}
EOF;

		$this->out('');
		$this->out($output);
	}

	/**
	 * Write a string to standard output.
	 *
	 * @param   string   $text  The text to display.
	 * @param   boolean  $nl    True (default) to append a new line at the end of the output string.
	 *
	 * @return  AbstractCommand  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function out($text = '', $nl = true)
	{
		$this->output->out($text, $nl);

		return $this;
	}

	/**
	 * Get a value from standard input.
	 *
	 * @param   string  $question  The question you want to ask user.
	 *
	 * @return  string  The input string from standard input.
	 *
	 * @since   1.0
	 */
	public function in($question = '')
	{
		if ($question)
		{
			$this->out($question, false);
		}

		return rtrim(fread(STDIN, 8192), "\n");
	}
}
