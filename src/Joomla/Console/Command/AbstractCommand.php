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
use Joomla\Console\Exception\CommandNotFoundException;
use Joomla\Console\Option\Option;
use Joomla\Console\Option\OptionSet;
use Joomla\Console\Prompter\PrompterInterface;
use Joomla\Input;

/**
 * Abstract Console class.
 *
 * @since  1.0
 */
abstract class AbstractCommand implements \ArrayAccess
{
	/**
	 * Console application.
	 *
	 * @var  AbstractCliApplication
	 *
	 * @since  1.0
	 */
	public $application;

	/**
	 * The Cli input object.
	 *
	 * @var Input\Cli
	 *
	 * @since  1.0
	 */
	protected $input;

	/**
	 * The cli output object.
	 *
	 * @var CliOutput
	 *
	 * @since  1.0
	 */
	protected $output;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $name;

	/**
	 * The children(SubCommends) storage.
	 *
	 * @var array
	 *
	 * @since  1.0
	 */
	protected $children = array();

	/**
	 * The Options storage.
	 *
	 * @var OptionSet
	 *
	 * @since  1.0
	 */
	protected $options = null;

	/**
	 * Global Options.
	 *
	 * @var OptionSet
	 *
	 * @since  1.0
	 */
	protected $globalOptions = null;

	/**
	 * The command description.
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $description;

	/**
	 * The manual about this command.
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $help;

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $usage = '%s <cmd><command></cmd> <option>[option]</option>';

	/**
	 * The closure to execute.
	 *
	 * @var  callable
	 *
	 * @since  1.0
	 */
	protected $handler;

	/**
	 * The parent Console if this is a sub comment.
	 *
	 * @var AbstractCommand
	 *
	 * @since  1.0
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
	 *
	 * @since  1.0
	 */
	public function __construct($name = null, Input\Cli $input = null, CliOutput $output = null, AbstractCommand $parent = null)
	{
		$this->name   = $name   ?: $this->name;
		$this->input  = $input  ?: new Input\Cli;
		$this->output = $output ?: new Stdout;
		$this->parent = $parent;

		$this->options       = new OptionSet;
		$this->globalOptions = new OptionSet;

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
	 *
	 * @since  1.0
	 */
	public function execute()
	{
		if (count($this->children) && count($this->input->args))
		{
			$name = $this->input->args[0];

			try
			{
				return $this->executeSubCommand($name);
			}
			catch (CommandNotFoundException $e)
			{
				$e->getCommand()->renderAlternatives($e->getChild(), $e);

				return $e->getCode();
			}
			catch (\Exception $e)
			{
				$this->renderException($e);

				return $e->getCode();
			}
		}

		if ($this->handler)
		{
			if ($this->handler instanceof \Closure)
			{
				$code = $this->handler;

				return $code($this);
			}
			elseif (is_callable($this->handler))
			{
				return call_user_func($this->handler, $this);
			}
		}

		return $this->doExecute();
	}

	/**
	 * Execute this command.
	 *
	 * @throws \LogicException
	 *
	 * @return mixed
	 *
	 * @since  1.0
	 */
	protected function doExecute()
	{
		throw new \LogicException('You must override the doExecute() method in the concrete command class.');
	}

	/**
	 * Configure command.
	 *
	 * @return void
	 *
	 * @since  1.0
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
	 * @throws  CommandNotFoundException
	 * @return  mixed
	 *
	 * @since  1.0
	 */
	protected function executeSubCommand($name, Input\Cli $input = null, CliOutput $output = null)
	{
		if (empty($this->children[$name]))
		{
			throw new CommandNotFoundException(sprintf('Command "%s" not found.', $name), $this, $name);
		}

		/** @var $subCommand AbstractCommand */
		$subCommand = $this->children[$name];

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

		if (!$subCommand->getApplication())
		{
			$subCommand->setApplication($this->application);
		}

		return $subCommand->execute();
	}

	/**
	 * Input setter.
	 *
	 * @param   Input\Cli  $input  The Cli Input object.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since  1.0
	 */
	public function setInput(Input\Cli $input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Get Input object.
	 *
	 * @return Input\Cli
	 *
	 * @since  1.0
	 */
	public function getInput()
	{
		return $this->input;
	}

	/**
	 * Output setter.
	 *
	 * @param   CliOutput  $output  The Cli Output object.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since  1.0
	 */
	public function setOutput(CliOutput $output)
	{
		$this->output = $output;

		return $this;
	}

	/**
	 * Get Output object.
	 *
	 * @return CliOutput
	 *
	 * @since  1.0
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * Parent command setter.
	 *
	 * @param   AbstractCommand  $parent  The parent comment.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since  1.0
	 */
	public function setParent(AbstractCommand $parent = null)
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Get Parent Command.
	 *
	 * @return  AbstractCommand
	 *
	 * @since  1.0
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Add an argument(sub command) setting.
	 *
	 * @param   string|AbstractCommand  $command      The argument name or Console object.
	 *                                                If we just send a string, the object will auto create.
	 * @param   null                    $description  Console description.
	 * @param   array                   $options      Console options.
	 * @param   \Closure                $code         The closure to execute.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function addCommand($command, $description = null, $options = array(), \Closure $code = null)
	{
		if (!($command instanceof AbstractCommand))
		{
			$command = new static($command, $this->input, $this->output, $this);
		}

		// Set argument detail
		$command->setApplication($this->application)
			->setInput($this->input);

		if ($description !== null)
		{
			$command->setDescription($description);
		}

		if (count($options))
		{
			$command->setOptions($options);
		}

		if ($code)
		{
			$command->setHandler($code);
		}

		// Set parent
		$command->setParent($this);

		// Set global options to sub command
		/** @var $option Option */
		foreach ($this->globalOptions as $option)
		{
			$command->addOption($option);
		}

		$name  = $command->getName();

		$this->children[$name] = $command;

		return $this;
	}

	/**
	 * Get argument by offset or return default.
	 *
	 * @param   int             $offset   Argument offset.
	 * @param   callable|mixed  $default  Default value, if is a callable, will execute it.
	 *
	 * @return  null|string  Values from argument or user input.
	 */
	public function getArgument($offset, $default = null)
	{
		$args = $this->input->args;

		if (isset($args[$offset]))
		{
			return $args[$offset];
		}

		if (is_callable($default))
		{
			return $default();
		}

		return $default;
	}

	/**
	 * Alias of addCommand for legacy.
	 *
	 * @param   string|AbstractCommand  $argument     The argument name or Console object.
	 *                                                If we just send a string, the object will auto create.
	 * @param   null                    $description  Console description.
	 * @param   array                   $options      Console options.
	 * @param   \Closure                $code         The closure to execute.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since      1.0
	 * @deprecated This method will be removed.
	 */
	public function addArgument($argument, $description = null, $options = array(), \Closure $code = null)
	{
		return $this->addCommand($argument, $description, $options, $code);
	}

	/**
	 * Alias of addCommand if someone think child is more semantic.
	 *
	 * @param   string|AbstractCommand  $argument     The argument name or Console object.
	 *                                                If we just send a string, the object will auto create.
	 * @param   null                    $description  Console description.
	 * @param   array                   $options      Console options.
	 * @param   \Closure                $code         The closure to execute.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function addChild($argument, $description = null, $options = array(), \Closure $code = null)
	{
		return $this->addCommand($argument, $description, $options, $code);
	}

	/**
	 * Get a argument(command) by name path.
	 *
	 * @param   string  $path  Command name path.
	 *
	 * @return  AbstractCommand|null  Return command or null.
	 *
	 * @since  1.0
	 */
	public function getChild($path)
	{
		$path    = str_replace(array('/', '\\'), '\\', $path);
		$names   = explode('\\', $path);
		$command = $this;

		foreach ($names as $name)
		{
			if (isset($command[$name]))
			{
				$command = $command[$name];

				continue;
			}

			return null;
		}

		return $command;
	}

	/**
	 * Get children array.
	 *
	 * @return array  children.
	 *
	 * @since  1.0
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Batch set children (sub commands).
	 *
	 * @param   array  $children  An array include argument objects.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setChildren($children)
	{
		$children = (array) $children;

		foreach ($children as $argument)
		{
			$this->addCommand($argument);
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
	 *
	 * @since   1.0
	 */
	public function addOption($option, $default = null, $description = null, $global = false)
	{
		if (!($option instanceof Option))
		{
			$option = new Option($option, $default, $description, $global);
		}

		$option->setInput($this->input);

		$name   = $option->getName();
		$global = $option->isGlobal();

		if ($global)
		{
			$this->globalOptions[$name] = $option;

			// Global option should not equal to private option
			unset($this->options[$name]);

			// We should pass global option to all children.
			foreach ($this->children as $child)
			{
				$child->addOption($option);
			}
		}
		else
		{
			$this->options[$name] = $option;

			// Global option should not equal to private option
			unset($this->globalOptions[$name]);
		}

		return $this;
	}

	/**
	 * Get value from an option.
	 *
	 * If the name not found, we use alias to find options.
	 *
	 * @param   string  $name     The option name.
	 * @param   string  $default  The default value when option not set.
	 *
	 * @return  mixed  The option value we want to get or default value if option not exists.
	 *
	 * @since   1.0
	 */
	public function getOption($name, $default = null)
	{
		// Get from private
		$option = $this->options[$name];

		if (!$option)
		{
			$option = $this->globalOptions[$name];
		}

		if ($option instanceof Option)
		{
			$option->setInput($this->input);

			return $option->getValue();
		}
		else
		{
			return $default;
		}
	}

	/**
	 * Get options as array.
	 *
	 * @param   boolean  $global  is Global options.
	 *
	 * @return  mixed  The options array.
	 *
	 * @since   1.0
	 */
	public function getOptions($global = false)
	{
		return $global ? (array) $this->options : (array) $this->globalOptions;
	}

	/**
	 * Get option set object.
	 *
	 * @param   boolean  $global  is Global options.
	 *
	 * @return  mixed  The options array.
	 *
	 * @since   1.0
	 */
	public function getOptionSet($global = false)
	{
		return $global ? $this->globalOptions : $this->options;
	}

	/**
	 * Get all options include global.
	 *
	 * @return array  The options array.
	 *
	 * @since  1.0
	 */
	public function getAllOptions()
	{
		return array_merge((array) $this->globalOptions, (array) $this->options);
	}

	/**
	 * Batch add options to command.
	 *
	 * @param   mixed  $options  An options array.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since  1.0
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
	 * set the option alias.
	 *
	 * @param   mixed   $aliases  The alias to map this option.
	 * @param   string  $name     The option name.
	 * @param   bool    $global   Is global option?
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setOptionAlias($aliases, $name, $global = false)
	{
		if ($global)
		{
			$this->globalOptions->setAlias($aliases, $name);
		}
		else
		{
			$this->options->setAlias($aliases, $name);
		}

		return $this;
	}

	/**
	 * The command description getter.
	 *
	 * @return string  Console description.
	 *
	 * @since  1.0
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
	 *
	 * @since   1.0
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
	 *
	 * @since  1.0
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
	 *
	 * @since   1.0
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
	 *
	 * @since   1.0
	 */
	public function getHandler()
	{
		return $this->handler;
	}

	/**
	 * Console execute code setter.
	 *
	 * @param   callable  $handler  Console execute handler.
	 *
	 * @return  AbstractCommand  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setHandler($handler = null)
	{
		$this->handler = $handler;

		return $this;
	}

	/**
	 * Get the application.
	 *
	 * @return AbstractCliApplication  Console application.
	 *
	 * @since  1.0
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
	 *
	 * @since   1.0
	 */
	public function setApplication($application)
	{
		$this->application = $application;

		return $this;
	}

	/**
	 * Get the help manual.
	 *
	 * @return string  Help of this Command.
	 *
	 * @since  1.0
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
	 *
	 * @since   1.0
	 */
	public function setHelp($help)
	{
		$this->help = $help;

		return $this;
	}

	/**
	 * Get the usage.
	 *
	 * @return string  Usage of this command.
	 *
	 * @since  1.0
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
	 *
	 * @since   1.0
	 */
	public function setUsage($usage)
	{
		$this->usage = $usage;

		return $this;
	}

	/**
	 * Render auto complete alternatives.
	 *
	 * @param   string                    $wrongName  The wrong command name to auto completed.
	 * @param   CommandNotFoundException  $exception  The exception of wrong argument.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function renderAlternatives($wrongName, $exception)
	{
		/** @var $exception \InvalidArgumentException */
		$message      = $exception->getMessage();
		$autoComplete = '';
		$alternatives = array();

		// Autocomplete
		foreach ($this->children as $command)
		{
			/** @var $command Command */
			$commandName = $command->getName();

			/*
			 * Here we use "Levenshtein distance" to compare wrong name with every command names.
			 *
			 * If the difference number less than 1/3 of wrong name which user typed, means this is a similar name,
			 * we can notice user to choose these similar names.
			 *
			 * And if the string of wrong name can be found in a command name, we also notice user to choose it.
			 */
			if (levenshtein($wrongName, $commandName) <= (strlen($wrongName) / 3) || strpos($commandName, $wrongName) !== false)
			{
				$alternatives[] = "    " . $commandName;
			}
		}

		if (count($alternatives))
		{
			$autoComplete = "Did you mean one of these?\n";
			$autoComplete .= implode($alternatives);
		}

		$this->out('');
		$this->err("<error>{$message}</error>");
		$this->out('');
		$this->err($autoComplete);
	}

	/**
	 * Render exception for debugging.
	 *
	 * @param   \Exception  $exception  The exception we want to render.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
		$this->err($output);
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
	 * Write a string to standard error output.
	 *
	 * @param   string   $text  The text to display.
	 * @param   boolean  $nl    True (default) to append a new line at the end of the output string.
	 *
	 * @return  AbstractCommand  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function err($text = '', $nl = true)
	{
		if ($this->output instanceof \Joomla\Console\Output\Stdout)
		{
			$this->output->err($text, $nl);
		}
		else
		{
			$this->output->out($text, $nl);
		}

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

		return rtrim(fread(STDIN, 8192), "\n\r");
	}

	/**
	 * Set child command, note the key is no use, we use command name as key.
	 *
	 * @param   mixed            $offset  No use here.
	 * @param   AbstractCommand  $value   Command object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function offsetSet($offset, $value)
	{
		$this->addCommand($value);
	}

	/**
	 * Is a child exists?
	 *
	 * @param   string  $offset  The command name to get command.
	 *
	 * @return  boolean  True if command exists.
	 *
	 * @since   1.0
	 */
	public function offsetExists($offset)
	{
		return isset($this->children[$offset]);
	}

	/**
	 * Unset a child command.
	 *
	 * @param   string  $offset  The command name to remove.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function offsetUnset($offset)
	{
		unset($this->children[$offset]);
	}

	/**
	 * Get a command by name.
	 *
	 * @param   string  $offset  The command name to get command.
	 *
	 * @return  AbstractCommand|null  Return command object if found.
	 *
	 * @since   1.0
	 */
	public function offsetGet($offset)
	{
		return isset($this->children[$offset]) ? $this->children[$offset] : null;
	}
}
