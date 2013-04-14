<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller\Tests;

use Joomla\Controller\AbstractController;

/**
 * Joomla Framework Capitaliser Object Class
 *
 * @since  1.0
 */

/**
 * Concrete class extending JControllerBase.
 *
 * @since  1.0
 */
class BaseController extends AbstractController
{
	/**
	 * Method to execute the controller.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		return 'base';
	}
}
