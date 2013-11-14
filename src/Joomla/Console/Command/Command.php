<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Command;

/**
 * Base Command class.
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
	 * @return  void
	 *
	 * @since   1.0
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
	 * Execute this command.
	 *
	 * @return  mixed  Executed result or exit code.
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		if (count($this->input->args) && $this->input->args[0] != 'help'
			&& $this->getOption('h') && !$this->getParent())
		{
			array_unshift($this->input->args, 'help');
		}

		if ($this->getOption('no-ansi'))
		{
			$this->output->getProcessor()->noColors = true;
		}

		return parent::execute();
	}

	/**
	 * Execute this command.
	 *
	 * @return int
	 *
	 * @since  1.0
	 */
	protected function doExecute()
	{
		$this->input->args = array($this->name);

		$output = $this->application
			->getDefaultCommand()
			->getArgument('help')
			->setInput($this->input)
			->setOutput($this->output)
			->execute();

		$this->out($output);

		return;
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
