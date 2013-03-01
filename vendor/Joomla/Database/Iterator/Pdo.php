<?php
/**
 * @package     Joomla\Framework
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Iterator;


use Joomla\Database\DatabaseIterator;
use PDOStatement;

/**
 * PDO database iterator.
 *
 * @package     Joomla\Framework
 * @subpackage  Database
 * @since       12.1
 */
class Pdo extends DatabaseIterator
{
	/**
	 * Get the number of rows in the result set for the executed SQL given by the cursor.
	 *
	 * @return  integer  The number of rows in the result set.
	 *
	 * @since   12.1
	 * @see     Countable::count()
	 */
	public function count()
	{
		if (!empty($this->cursor) && $this->cursor instanceof PDOStatement)
		{
			return $this->cursor->rowCount();
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Method to fetch a row from the result set cursor as an object.
	 *
	 * @return  mixed   Either the next row from the result set or false if there are no more rows.
	 *
	 * @since   12.1
	 */
	protected function fetchObject()
	{
		if (!empty($this->cursor) && $this->cursor instanceof PDOStatement)
		{
			return $this->cursor->fetchObject($this->class);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Method to free up the memory used for the result set.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	protected function freeResult()
	{
		if (!empty($this->cursor) && $this->cursor instanceof PDOStatement)
		{
			$this->cursor->closeCursor();
		}
	}
}
