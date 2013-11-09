<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Descriptor;

/**
 * Interface DescriptorInterface
 *
 * @package  Joomla\Console\AbstractDescriptor
 * @since    1.0
 */
interface DescriptorInterface
{
	/**
	 * Add an item to describe.
	 *
	 * @param   mixed  $item  The item you want to describe.
	 *
	 * @return  DescriptorInterface  Return this object to support chaining.
	 */
	public function addItem($item);

	/**
	 * Render all items description.
	 *
	 * @return  string
	 */
	public function render();
}
