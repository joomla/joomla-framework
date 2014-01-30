<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Sqlsrv;

use Joomla\Database\DatabaseIterator;

/**
 * SQL Server Database Iterator.
 *
 * @since  1.0
 */
class SqlsrvIterator extends DatabaseIterator
{
	/**
	 * Get the number of rows in the result set for the executed SQL given by the cursor.
	 *
	 * @return  integer  The number of rows in the result set.
	 *
	 * @since   1.0
	 * @see     Countable::count()
	 */
	public function count()
	{
		return sqlsrv_num_rows($this->cursor);
	}

	/**
	 * Method to fetch a row from the result set cursor as an object.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 *
	 * @since   1.0
	 */
	protected function fetchObject()
	{
		return sqlsrv_fetch_object($this->cursor, $this->class);
	}

	/**
	 * Method to free up the memory used for the result set.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function freeResult()
	{
		sqlsrv_free_stmt($this->cursor);
	}
}
