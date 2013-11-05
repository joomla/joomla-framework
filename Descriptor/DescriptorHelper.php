<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

use Joomla\Console\Command\Command;

/**
 * A descriptor helper to get different descriptor and render it.
 *
 * @since  1.0
 */
class DescriptorHelper implements DescriptorHelperInterface
{
	/**
	 * @var DescriptorInterface
	 */

	private $commendDescriptor;

	/**
	 * @var DescriptorInterface
	 */
	private $optionDescriptor;

	/**
	 * @var DescriptorInterface
	 */
	private $consoleDescriptor;

	/**
	 * The class constructor.
	 *
	 * @param   DescriptorInterface  $commendDescriptor  Command descriptor.
	 * @param   DescriptorInterface  $optionDescriptor   Option descriptor.
	 * @param   DescriptorInterface  $consoleDescriptor  Console descriptor..
	 */
	public function __construct(DescriptorInterface $commendDescriptor = null,
		DescriptorInterface $optionDescriptor = null, DescriptorInterface $consoleDescriptor = null)
	{
		$this->commendDescriptor = $commendDescriptor;
		$this->optionDescriptor  = $optionDescriptor;
		$this->consoleDescriptor = $consoleDescriptor;
	}

	/**
	 * Describe a command detail.
	 *
	 * @param   Command  $command  The command to described.
	 *
	 * @return  string  Return the described text.
	 */
	public function describe(Command $command)
	{
		// Describe Options
		$options = $command->getAllOptions();

		$optionDescriptor = $this->getOptionDescriptor();

		foreach ($options as $option)
		{
			$optionDescriptor->addItem($option);
		}

		$render['option'] = $optionDescriptor->render();

		// Describe Commands
		$commands = $command->getArguments();

		$commandDescriptor = $this->getCommendDescriptor();

		foreach ($commands as $command)
		{
			$commandDescriptor->addItem($command);
		}

		$render['command'] = $commandDescriptor->render();

		// Describe Console
		$consoleDescriptor = $this->getConsoleDescriptor();

		$template = $consoleDescriptor->addItem($command->getApplication())
			->render();

		return str_replace(
			array('{OPTIONS}', '{COMMANDS}'),
			$render,
			$template
		);
	}

	/**
	 * @return \Joomla\Console\Descriptor\DescriptorInterface
	 */
	public function getCommendDescriptor()
	{
		return $this->commendDescriptor;
	}

	/**
	 * @param \Joomla\Console\Descriptor\DescriptorInterface $commendDescriptor
	 */
	public function setCommendDescriptor($commendDescriptor)
	{
		$this->commendDescriptor = $commendDescriptor;

		return $this;
	}

	/**
	 * @return \Joomla\Console\Descriptor\DescriptorInterface
	 */
	public function getOptionDescriptor()
	{
		return $this->optionDescriptor;
	}

	/**
	 * @param \Joomla\Console\Descriptor\DescriptorInterface $optionDescriptor
	 */
	public function setOptionDescriptor($optionDescriptor)
	{
		$this->optionDescriptor = $optionDescriptor;

		return $this;
	}

	/**
	 * @return \Joomla\Console\Descriptor\DescriptorInterface
	 */
	public function getConsoleDescriptor()
	{
		return $this->consoleDescriptor;
	}

	/**
	 * @param \Joomla\Console\Descriptor\DescriptorInterface $consoleDescriptor
	 */
	public function setConsoleDescriptor($consoleDescriptor)
	{
		$this->consoleDescriptor = $consoleDescriptor;

		return $this;
	}
}