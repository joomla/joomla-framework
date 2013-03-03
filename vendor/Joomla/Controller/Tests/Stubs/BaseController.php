<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller\Tests;

use Joomla\Controller\Base;

/**
 * Joomla Platform Capitaliser Object Class
 *
 * @package  Joomla\Framework\Test
 * @since    1.0
 */

/**
 * Concrete class extending JControllerBase.
 *
 * @package  Joomla\Framework\Test
 * @since    12.1
 */
class BaseController extends Base
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
