<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Command;

use Joomla\Application\Cli\ColorStyle;
use Joomla\Console;
use Joomla\Console\Descriptor\ConsoleDescriptor;
use Joomla\Console\Descriptor\DescriptorHelper;
use Joomla\Console\Descriptor\CommandDescriptor;
use Joomla\Console\Descriptor\DescriptorHelperInterface;
use Joomla\Console\Descriptor\OptionDescriptor;

class ListCommand extends Command
{
	/**
	 * The Descriptor Helper.
	 *
	 * @var  DescriptorHelperInterface
	 */
	protected $descriptor;

	/**
	 * Configure command.
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('list')
			->setDescription('Lists commands Lists commands Lists commands
			Lists commands Lists commands');
	}

	/**
	 * doExecute
	 *
	 */
	protected function doExecute()
	{
		// Add a blue style <option>
		$this->output
			->getProcessor()
			->addStyle('option', new Colorstyle('cyan', '', array('bold')))
			->addStyle('cmd', new Colorstyle('magenta', '', array('bold')));

		$args = $this->input->args;

		$command = $this->getDescribedCommand($args);

		$descriptor = $this->getDescriptor();

		$rendered = $descriptor->describe($command);

		$this->output->out($rendered);

		return 255;
	}

	/**
	 * getDescribedCommand
	 *
	 * @param $args
	 *
	 * @return Command|null
	 * @throws \LogicException
	 */
	protected function getDescribedCommand($args)
	{
		$command = $this->getParent();

		foreach ($args as $arg)
		{
			$command = $command->getArgument($arg);

			if (!$command)
			{
				throw new \LogicException(sprintf('Command: %s not found.', implode(' ', $args)));
			}
		}

		return $command;
	}

	/**
	 * getDescriptor
	 *
	 * @return DescriptorHelperInterface|DescriptorHelper
	 */
	public function getDescriptor()
	{
		if (!$this->descriptor)
		{
			$this->descriptor = new DescriptorHelper(
				new CommandDescriptor,
				new OptionDescriptor,
				new ConsoleDescriptor
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