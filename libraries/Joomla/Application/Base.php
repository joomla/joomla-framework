<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application;

defined('JPATH_PLATFORM') or die;

/**
 * Joomla Platform Base Application Class
 *
 * @package     Joomla.Platform
 * @subpackage  Application
 * @since       12.1
 */
abstract class Base
{
	/**
	 * The application input object.
	 *
	 * @var    JInput
	 * @since  12.1
	 */
	public $input = null;

	/**
	 * Method to close the application.
	 *
	 * @param   integer  $code  The exit code (optional; default is 0).
	 *
	 * @return  void
	 *
	 * @codeCoverageIgnore
	 * @since   12.1
	 */
	public function close($code = 0)
	{
		exit($code);
	}
}
