<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;

use Joomla\Input;
use Joomla\Application\Cli\Output\Stdout;

/**
 * A password prompter supports hidden input.
 *
 * @since  1.0
 */
class PasswordPrompter extends CallbackPrompter
{
	/**
	 * Which shell we use.
	 *
	 * @var string
	 *
	 * @since  1.0
	 */
	protected static $shell;

	/**
	 * Is stty available?
	 *
	 * @var boolean
	 *
	 * @since  1.0
	 */
	protected static $stty;

	/**
	 * Is Windows OS?
	 *
	 * @var  boolean
	 *
	 * @since  1.0
	 */
	protected $win = false;

	/**
	 * The Hidden input exe poath for Windows OS.
	 *
	 * @see https://github.com/Seldaek/hidden-input
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $hiddenExe = null;

	/**
	 * Constructor.
	 *
	 * @param   string     $question  The question you want to ask.
	 * @param   $default   $default   The default value.
	 * @param   Input\Cli  $input     The input object.
	 * @param   Stdout     $output    The output object.
	 *
	 * @since   1.0
	 */
	function __construct($question = null, $default = null,  Input\Cli $input = null, Stdout $output = null)
	{
		$this->win = defined('PHP_WINDOWS_VERSION_BUILD');

		$this->hiddenExe = __DIR__ . '/../bin/hiddeninput.exe';

		parent::__construct($question, $default, $input, $output);
	}

	/**
	 * Show prompt to ask user.
	 *
	 * @param   string  $msg      Question.
	 * @param   string  $default  Default value.
	 *
	 * @return  string  The value that use input.
	 *
	 * @since   1.0
	 */
	public function ask($msg = '', $default = null)
	{
		return $this->in($msg) ? : $default;
	}

	/**
	 * Get a value from standard input.
	 *
	 * @param   string  $question  The question you want to ask user.
	 *
	 * @throws  \RuntimeException
	 *
	 * @return  string  The input string from standard input.
	 *
	 * @since   1.0
	 */
	public function in($question = '')
	{
		$question ? : $this->question;

		if ($this->win)
		{
			if ($question)
			{
				$this->output->out()->out($question, false);
			}

			$value = rtrim(shell_exec($this->hiddenExe));

			$this->output->out();

			return $value;
		}

		// Using stty help us test this class.
		elseif ($this->findStty())
		{
			if ($question)
			{
				$this->output->out()->out($question, false);
			}

			// Get stty setting
			$setting = shell_exec('stty -g');

			shell_exec('stty -echo');

			$value = fread($this->inputStream, 8192);

			shell_exec(sprintf('stty %s', $setting));

			if ($value === false)
			{
				throw new \RuntimeException('Cannot get input value.');
			}

			$this->output->out();

			return rtrim($value);
		}

		// For linux & Unix system
		else
		{
			// Find shell.
			$shell = $this->findShell();

			if (!$shell)
			{
				throw new \RuntimeException("Can't invoke shell");
			}

			$this->output->out();

			// Using read to write password
			$read = sprintf('read -s -p "%s" mypassword && echo $mypassword', $question);

			// Here we use bash to handle this command.
			$command = sprintf("/usr/bin/env bash -c '%s'", $read);

			$value = rtrim(shell_exec($command));

			$this->output->out();

			return $value;
		}
	}

	/**
	 * Find which shell we use (only in UNIX & LINUX).
	 *
	 * @return  string  Shell name.
	 *
	 * @throws  \RuntimeException
	 *
	 * @since   1.0
	 */
	protected function findShell()
	{
		if (self::$shell)
		{
			return self::$shell;
		}

		$command = "/usr/bin/env %s -c 'echo Hello'";

		foreach (array('bash', 'zsh', 'ksh', 'csh') as $shell)
		{
			if (rtrim(shell_exec(sprintf($command, $shell))) === 'Hello')
			{
				self::$shell = $shell;

				return $shell;
			}
		}

		return null;
	}

	/**
	 * Find stty (only in UNIX & LINUX).
	 *
	 * @return  boolean  Stty exists or not.
	 *
	 * @since   1.0
	 */
	protected function findStty()
	{
		if (null !== self::$stty)
		{
			return self::$stty;
		}

		exec('stty 2>&1', $output, $exitcode);

		return self::$stty = ($exitcode === 0);
	}
}
