<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Command;

use Joomla\Application\Cli\ColorStyle;
use Joomla\Console\Descriptor\ConsoleDescriptor;
use Joomla\Console\Descriptor\AbstractDescriptorHelper;
use Joomla\Console\Descriptor\DescriptorHelperInterface;
use Joomla\Console\Descriptor\Text\TextCommandDescriptor;
use Joomla\Console\Descriptor\Text\TextDescriptorHelper;
use Joomla\Console\Descriptor\Text\TextOptionDescriptor;
use Joomla\Console\Exception\CommandNotFoundException;

/**
 * Command to list all arguments.
 *
 * @since  1.0
 */
class HelpCommand extends Command
{
	/**
	 * Command(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'help';

	/**
	 * The AbstractDescriptor Helper.
	 *
	 * @var  DescriptorHelperInterface
	 */
	protected $descriptor;

	/**
	 * The command we want to described.
	 *
	 * @var  Command
	 */
	protected $describedCommand;

	/**
	 * Configure command.
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setDescription('List all arguments and show usage & manual.');
	}

	/**
	 * Execute this command.
	 *
	 * @return int The exit code.
	 */
	protected function doExecute()
	{
		// Add a blue style <option>
		$this->output
			->getProcessor()
			->addStyle('option', new Colorstyle('cyan',    '', array('bold')))
			->addStyle('cmd',    new Colorstyle('magenta', '', array('bold')));

		$args = $this->input->args;

		$command = $this->getDescribedCommand($args);

		$descriptor = $this->getDescriptor();

		$rendered = $descriptor->describe($command);

		$this->out($rendered);

		return 0;
	}

	/**
	 * getDescribedCommand
	 *
	 * @param $args
	 *
	 * @return Command
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function getDescribedCommand($args)
	{
		$this->describedCommand = $command = $this->getParent();

		foreach ($args as $arg)
		{
			$command = $command->getArgument($arg);

			if (!$command)
			{
				throw new CommandNotFoundException(sprintf('Command: "%s" not found.', implode(' ', $args)), $this->describedCommand, $arg);
			}

			// Set current to describedCommand that we can use it auto complete wrong args.
			$this->describedCommand = $command;
		}

		return $command;
	}

	/**
	 * getDescriptor

	 *
*@return DescriptorHelperInterface|AbstractDescriptorHelper
	 */
	public function getDescriptor()
	{
		if (!$this->descriptor)
		{
			$this->descriptor = new TextDescriptorHelper(
				new TextCommandDescriptor,
				new TextOptionDescriptor
			);
		}

		return $this->descriptor;
	}

	/**
	 * setDescriptor
	 *
	 * @param $descriptor
	 *
	 * @return $this
	 */
	public function setDescriptor(DescriptorHelperInterface $descriptor)
	{
		$this->descriptor = $descriptor;

		return $this;
	}
}