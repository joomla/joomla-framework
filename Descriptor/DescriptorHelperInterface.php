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
interface DescriptorHelperInterface
{
	/**
	 * Describe a command detail.
	 *
	 * @param   Command  $command  The command to described.
	 *
	 * @return  string  Return the described text.
	 */
	public function describe(Command $command);
}