<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

use Joomla\Console\Command\Command;
use Joomla\Console\Console;

/**
 * A descriptor helper to get different descriptor and render it.
 *
 * @since  1.0
 */
abstract class AbstractDescriptorHelper implements DescriptorHelperInterface
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
	 * The class constructor.
	 *
	 * @param   DescriptorInterface  $commendDescriptor  Command descriptor.
	 * @param   DescriptorInterface  $optionDescriptor   Option descriptor.
	 */
	public function __construct(DescriptorInterface $commendDescriptor = null, DescriptorInterface $optionDescriptor = null)
	{
		$this->commendDescriptor = $commendDescriptor;
		$this->optionDescriptor  = $optionDescriptor;
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
	 * @param \Joomla\Console\Descriptor\DescriptorInterface $helpDescriptor
	 */
	public function setHelpDescriptor($helpDescriptor)
	{
		$this->helpDescriptor = $helpDescriptor;

		return $this;
	}
}