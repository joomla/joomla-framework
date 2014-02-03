<?php
/**
 * Part of jframework project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
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

		$this->hidenExe = __DIR__ . '/../bin/hiddeninput.exe';

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
		// if ($this->win)
		if (false)
		{
			if ($question)
			{
				$this->output->out($question, false);
			}

			$value = rtrim(shell_exec($this->hidenExe));

			$this->output->out();

			return $value;
		}

		else
		{
			$command = "/usr/bin/env bash -c 'echo OK'";

			if (rtrim(shell_exec($command)) !== 'OK')
			{
				throw new \RuntimeException("Can't invoke bash");
			}

			$command = "/usr/bin/env bash -c 'read -s -p "
      . addslashes($question)
      . " mypassword && echo \$mypassword'";

    $password = rtrim(shell_exec($command));
    echo "\n";
    return $password;
		}
	}

}
 