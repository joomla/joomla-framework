<?php
/**
 * @package     Joomla\Framework
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database;


/**
 * Joomla Platform Database Interface
 *
 * @package     Joomla\Framework
 * @subpackage  Database
 * @since       11.2
 */
interface DatabaseInterface
{
	/**
	 * Test to see if the connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   11.2
	 */
	public static function isSupported();
}
