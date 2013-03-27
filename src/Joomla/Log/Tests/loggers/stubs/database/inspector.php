<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Inspector classes for the JLog package.
 */

/**
 * JLogLoggerDatabaseInspector class.
 *
 * @since  1.0
 */
class JLogLoggerDatabaseInspector extends Joomla\Log\Logger\Database
{
	public $driver;

	public $host;

	public $user;

	public $password;

	public $database;

	public $table;

	public $dbo;

	/**
	 * Test...
	 *
	 * @return void
	 */
	public function connect()
	{
		parent::connect();
	}
}
