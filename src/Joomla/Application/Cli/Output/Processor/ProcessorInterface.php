<?php
/**
 * Part of the Joomla Framework Application Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Cli\Output\Processor;

/**
 * Class ProcessorInterface.
 *
 * @since  __DEPLOY_VERSION__
 */
interface ProcessorInterface
{
	/**
	 * Process the provided output into a string.
	 *
	 * @param   mixed
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function process($output);
}
