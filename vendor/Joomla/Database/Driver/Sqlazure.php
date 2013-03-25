<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Driver;

/**
 * SQL Server database driver
 *
 * @see    http://msdn.microsoft.com/en-us/library/ee336279.aspx
 * @since  1.0
 */
class Sqlazure extends Sqlsrv
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $name = 'sqlzure';
}
