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
	protected $commendDescriptor;

	/**
	 * @var DescriptorInterface
	 */
	protected $optionDescriptor;

	/**
	 * @var DescriptorInterface
	 */
	protected $consoleDescriptor;

	/**
	 * @var DescriptorInterface
	 */
	protected $helpDescriptor;

	/**
	 * The class constructor.
	 *
	 * @param   DescriptorInterface  $commendDescriptor  Command descriptor.
	 * @param   DescriptorInterface  $optionDescriptor   Option descriptor.
	 * @param   DescriptorInterface  $helpDescriptor     Help descriptor.
	 */
	public function __construct(DescriptorInterface $commendDescriptor = null,
		DescriptorInterface $optionDescriptor = null, DescriptorInterface $helpDescriptor = null)
	{
		$this->commendDescriptor = $commendDescriptor;
		$this->optionDescriptor  = $optionDescriptor;
		$this->helpDescriptor    = $helpDescriptor;
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
		$options          = $command->getAllOptions();
		$optionDescriptor = $this->getOptionDescriptor();

		foreach ($options as $option)
		{
			$optionDescriptor->addItem($option);
		}

		$render['option'] = $optionDescriptor->render();

		// Describe Commands
		$commands          = $command->getArguments();
		$commandDescriptor = $this->getCommendDescriptor();

		foreach ($commands as $cmd)
		{
			$commandDescriptor->addItem($cmd);
		}

		$render['command'] = $commandDescriptor->render();

		// Describe Help
		$helpDescriptor = $this->getHelpDescriptor();

		$template = $helpDescriptor->addItem($command)
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
	public function getHelpDescriptor()
	{
		return $this->helpDescriptor;
	}

	/**
	 * @param \Joomla\Console\Descriptor\DescriptorInterface $helpDescriptor
	 */
	public function setHelpDescriptor($helpDescriptor)
	{
		$this->helpDescriptor = $helpDescriptor;

		return $this;
	}
}