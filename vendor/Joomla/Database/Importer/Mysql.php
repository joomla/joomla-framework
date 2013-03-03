<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Importer;

use Joomla\Database\Driver\Mysql as DriverMysql;
use Exception;

/**
 * MySQL import driver.
 *
 * @package  Joomla\Framework
 * @since    11.1
 */
class Mysql extends Mysqli
{
	/**
	 * Checks if all data and options are in order prior to exporting.
	 *
	 * @return  Mysql  Method supports chaining.
	 *
	 * @since   11.1
	 * @throws  Exception if an error is encountered.
	 */
	public function check()
	{
		// Check if the db connector has been set.
		if (!($this->db instanceof DriverMysql))
		{
			throw new Exception('JPLATFORM_ERROR_DATABASE_CONNECTOR_WRONG_TYPE');
		}

		// Check if the tables have been specified.
		if (empty($this->from))
		{
			throw new Exception('JPLATFORM_ERROR_NO_TABLES_SPECIFIED');
		}

		return $this;
	}
}
