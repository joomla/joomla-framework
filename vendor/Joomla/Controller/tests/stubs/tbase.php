<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Concrete class extending JControllerBase.
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Controller
 * @since       12.1
 */
class BaseController extends Joomla\Controller\Base
{
	/**
	 * Method to execute the controller.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		return 'base';
	}
}
