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
 * Base Console class.
 *
 * @since  1.0
 */
class Command extends AbstractCommand
{
	/**
	 * Render exception for debugging.
	 *
	 * @param   \Exception  $exception  The exception we want to render.
	 *
	 * @return void
	 */
	public function renderException($exception)
	{
		if (!$this->getOption('v', 0))
		{
			$this->out('')->out($exception->getMessage());

			return;
		}

		parent::renderException($exception);
	}

	/**
	 * Write a string to standard output.
	 *
	 * @param   string   $text  The text to display.
	 * @param   boolean  $nl    True (default) to append a new line at the end of the output string.
	 *
	 * @return  Command  Instance of $this to allow chaining.
	 *
	 * @since   1.0
	 */
	public function out($text = '', $nl = true)
	{
		if (!$this->getOption('q', 0))
		{
			parent::out($text, $nl);
		}

		return $this;
	}
}
