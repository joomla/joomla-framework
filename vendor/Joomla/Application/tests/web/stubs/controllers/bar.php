<?php
/**
 * @package     Joomla\Framework\Test
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test stub controller.
 *
 * @package     Joomla\Framework\Test
 * @subpackage  Application
 * @since       12.3
 */
class TControllerBar extends Joomla\Controller\Base
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since   12.1
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		return true;
	}
}
