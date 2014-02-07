<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

/**
 * A descriptor helper to get different descriptor and render it.
 *
 * @since  1.0
 */
abstract class AbstractDescriptorHelper implements DescriptorHelperInterface
{
	/**
	 * Command descriptor.
	 *
	 * @var DescriptorInterface
	 *
	 * @since  1.0
	 */
	protected $CommandDescriptor;

	/**
	 * Option descriptor.
	 *
	 * @var DescriptorInterface
	 *
	 * @since  1.0
	 */
	protected $optionDescriptor;

	/**
	 * The class constructor.
	 *
	 * @param   DescriptorInterface  $CommandDescriptor  Command descriptor.
	 * @param   DescriptorInterface  $optionDescriptor   Option descriptor.
	 *
	 * @since   1.0
	 */
	public function __construct(DescriptorInterface $CommandDescriptor = null, DescriptorInterface $optionDescriptor = null)
	{
		$this->CommandDescriptor = $CommandDescriptor;
		$this->optionDescriptor  = $optionDescriptor;
	}

	/**
	 * Command descriptor getter.
	 *
	 * @return  DescriptorInterface
	 *
	 * @since   1.0
	 */
	public function getCommandDescriptor()
	{
		return $this->CommandDescriptor;
	}

	/**
	 * Command descriptor setter.
	 *
	 * @param   DescriptorInterface  $CommandDescriptor  Command descriptor.
	 *
	 * @return  $this Support chaining.
	 *
	 * @since   1.0
	 */
	public function setCommandDescriptor($CommandDescriptor)
	{
		$this->CommandDescriptor = $CommandDescriptor;

		return $this;
	}

	/**
	 * Option descriptor getter.
	 *
	 * @return \Joomla\Console\Descriptor\DescriptorInterface
	 *
	 * @since   1.0
	 */
	public function getOptionDescriptor()
	{
		return $this->optionDescriptor;
	}

	/**
	 * Option descriptor setter.
	 *
	 * @param   DescriptorInterface  $optionDescriptor  Option descriptor.
	 *
	 * @return  $this  Support chaining.
	 *
	 * @since   1.0
	 */
	public function setOptionDescriptor($optionDescriptor)
	{
		$this->optionDescriptor = $optionDescriptor;

		return $this;
	}
}
