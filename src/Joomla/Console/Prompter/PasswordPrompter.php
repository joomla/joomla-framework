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
 * Class Prompter
 *
 * @since 1.0
 */
class PasswordPrompter extends CallbackPrompter
{
	/**
	 * Property shell.
	 *
	 * @var string
	 */
	protected static $shell;

	/**
	 * Property stty.
	 *
	 * @var boolean
	 */
	protected static $stty;

	/**
	 * Property win.
	 *
	 * @var  boolean
	 */
	protected $win = false;

	/**
	 * Property hiddenExe.
	 *
	 * @var  null
	 */
	protected $hiddenExe = null;

	/**
	 * @param Input\Cli $input
	 * @param Stdout    $output
	 */
	function __construct(Input\Cli $input = null, Stdout $output = null)
	{
		$this->win = defined('PHP_WINDOWS_VERSION_BUILD');

		$this->hiddenExe = __DIR__ . '/../bin/hiddeninput.exe';

		parent::__construct($input, $output);
	}

	/**
	 * ask
	 *
	 * @param string $msg
	 * @param string $default
	 *
	 * @return  mixed
	 */
	public function ask($msg = '', $default = null)
	{
		return $this->in($msg) ? : $default;
	}

	public function in($question = '')
	{
		if ($this->win)
		{
			if ($question)
			{
				$this->output->out($question, false);
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
				$this->output->out($question, false);
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
	 * findShell
	 *
	 * @return  string
	 *
	 * @throws \RuntimeException
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
	 * findStty
	 *
	 * @return  bool
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
