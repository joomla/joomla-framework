<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Postgresql;

use Joomla\Database\DatabaseQuery;
use Joomla\Database\Query\QueryElement;
use Joomla\Database\Query\LimitableInterface;

/**
 * PostgreSQL Query Building Class.
 *
 * @since  1.0
 */
class PostgresqlQuery extends DatabaseQuery implements LimitableInterface
{
	/**
	 * The FOR UPDATE element used in "FOR UPDATE" lock
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $forUpdate = null;

	/**
	 * The FOR SHARE element used in "FOR SHARE" lock
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $forShare = null;

	/**
	 * The NOWAIT element used in "FOR SHARE" and "FOR UPDATE" lock
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $noWait = null;

	/**
	 * The LIMIT element
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $limit = null;

	/**
	 * The OFFSET element
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $offset = null;

	/**
	 * The RETURNING element of INSERT INTO
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected $returning = null;

	/**
	 * Magic function to convert the query to a string, only for PostgreSQL specific queries
	 *
	 * @return  string	The completed query.
	 *
	 * @since   1.0
	 */
	public function __toString()
	{
		$query = '';

		switch ($this->type)
		{
			case 'select':
				$query .= (string) $this->select;
				$query .= (string) $this->from;

				if ($this->join)
				{
					// Special case for joins
					foreach ($this->join as $join)
					{
						$query .= (string) $join;
					}
				}

				if ($this->where)
				{
					$query .= (string) $this->where;
				}

				if ($this->group)
				{
					$query .= (string) $this->group;
				}

				if ($this->having)
				{
					$query .= (string) $this->having;
				}

				if ($this->order)
				{
					$query .= (string) $this->order;
				}

				if ($this->forUpdate)
				{
					$query .= (string) $this->forUpdate;
				}
				else
				{
					if ($this->forShare)
					{
						$query .= (string) $this->forShare;
					}
				}

				if ($this->noWait)
				{
					$query .= (string) $this->noWait;
				}

				break;

			case 'update':
				$query .= (string) $this->update;
				$query .= (string) $this->set;

				if ($this->join)
				{
					$onWord = ' ON ';

					// Workaround for special case of JOIN with UPDATE
					foreach ($this->join as $join)
					{
						$joinElem = $join->getElements();

						$joinArray = explode($onWord, $joinElem[0]);

						$this->from($joinArray[0]);
						$this->where($joinArray[1]);
					}

					$query .= (string) $this->from;
				}

				if ($this->where)
				{
					$query .= (string) $this->where;
				}

				break;

			case 'insert':
				$query .= (string) $this->insert;

				if ($this->values)
				{
					if ($this->columns)
					{
						$query .= (string) $this->columns;
					}

					$elements = $this->values->getElements();

					if (!($elements[0] instanceof $this))
					{
						$query .= ' VALUES ';
					}

					$query .= (string) $this->values;

					if ($this->returning)
					{
						$query .= (string) $this->returning;
					}
				}

				break;

			default:
				$query = parent::__toString();
				break;
		}

		if ($this instanceof LimitableInterface)
		{
			$query = $this->processLimit($query, $this->limit, $this->offset);
		}

		return $query;
	}

	/**
	 * Clear data from the query or a specific clause of the query.
	 *
	 * @param   string  $clause  Optionally, the name of the clause to clear, or nothing to clear the whole query.
	 *
	 * @return  PostgresqlQuery  Returns this object to allow chaining.
	 *
	 * @since   1.0
	 */
	public function clear($clause = null)
	{
		switch ($clause)
		{
			case 'limit':
				$this->limit = null;
				break;

			case 'offset':
				$this->offset = null;
				break;

			case 'forUpdate':
				$this->forUpdate = null;
				break;

			case 'forShare':
				$this->forShare = null;
				break;

			case 'noWait':
				$this->noWait = null;
				break;

			case 'returning':
				$this->returning = null;
				break;

			case 'select':
			case 'update':
			case 'delete':
			case 'insert':
			case 'from':
			case 'join':
			case 'set':
			case 'where':
			case 'group':
			case 'having':
			case 'order':
			case 'columns':
			case 'values':
				parent::clear($clause);
				break;

			default:
				$this->type = null;
				$this->limit = null;
				$this->offset = null;
				$this->forUpdate = null;
				$this->forShare = null;
				$this->noWait = null;
				$this->returning = null;
				parent::clear($clause);
				break;
		}

		return $this;
	}

	/**
	 * Casts a value to a char.
	 *
	 * Ensure that the value is properly quoted before passing to the method.
	 *
	 * Usage:
	 * $query->select($query->castAsChar('a'));
	 *
	 * @param   string  $value  The value to cast as a char.
	 *
	 * @return  string  Returns the cast value.
	 *
	 * @since   1.0
	 */
	public function castAsChar($value)
	{
		return $value . '::text';
	}

	/**
	 * Concatenates an array of column names or values.
	 *
	 * Usage:
	 * $query->select($query->concatenate(array('a', 'b')));
	 *
	 * @param   array   $values     An array of values to concatenate.
	 * @param   string  $separator  As separator to place between each value.
	 *
	 * @return  string  The concatenated values.
	 *
	 * @since   1.0
	 */
	public function concatenate($values, $separator = null)
	{
		if ($separator)
		{
			return implode(' || ' . $this->quote($separator) . ' || ', $values);
		}
		else
		{
			return implode(' || ', $values);
		}
	}

	/**
	 * Gets the current date and time.
	 *
	 * @return  string  Return string used in query to obtain
	 *
	 * @since   1.0
	 */
	public function currentTimestamp()
	{
		return 'NOW()';
	}

	/**
	 * Sets the FOR UPDATE lock on select's output row
	 *
	 * @param   string  $table_name  The table to lock
	 * @param   string  $glue        The glue by which to join the conditions. Defaults to ',' .
	 *
	 * @return  PostgresqlQuery  FOR UPDATE query element
	 *
	 * @since   1.0
	 */
	public function forUpdate($table_name, $glue = ',')
	{
		$this->type = 'forUpdate';

		if (is_null($this->forUpdate))
		{
			$glue = strtoupper($glue);
			$this->forUpdate = new QueryElement('FOR UPDATE', 'OF ' . $table_name, "$glue ");
		}
		else
		{
			$this->forUpdate->append($table_name);
		}

		return $this;
	}

	/**
	 * Sets the FOR SHARE lock on select's output row
	 *
	 * @param   string  $table_name  The table to lock
	 * @param   string  $glue        The glue by which to join the conditions. Defaults to ',' .
	 *
	 * @return  PostgresqlQuery  FOR SHARE query element
	 *
	 * @since   1.0
	 */
	public function forShare($table_name, $glue = ',')
	{
		$this->type = 'forShare';

		if (is_null($this->forShare))
		{
			$glue = strtoupper($glue);
			$this->forShare = new QueryElement('FOR SHARE', 'OF ' . $table_name, "$glue ");
		}
		else
		{
			$this->forShare->append($table_name);
		}

		return $this;
	}

	/**
	 * Used to get a string to extract year from date column.
	 *
	 * Usage:
	 * $query->select($query->year($query->quoteName('dateColumn')));
	 *
	 * @param   string  $date  Date column containing year to be extracted.
	 *
	 * @return  string  Returns string to extract year from a date.
	 *
	 * @since   1.0
	 */
	public function year($date)
	{
		return 'EXTRACT (YEAR FROM ' . $date . ')';
	}

	/**
	 * Used to get a string to extract month from date column.
	 *
	 * Usage:
	 * $query->select($query->month($query->quoteName('dateColumn')));
	 *
	 * @param   string  $date  Date column containing month to be extracted.
	 *
	 * @return  string  Returns string to extract month from a date.
	 *
	 * @since   1.0
	 */
	public function month($date)
	{
		return 'EXTRACT (MONTH FROM ' . $date . ')';
	}

	/**
	 * Used to get a string to extract day from date column.
	 *
	 * Usage:
	 * $query->select($query->day($query->quoteName('dateColumn')));
	 *
	 * @param   string  $date  Date column containing day to be extracted.
	 *
	 * @return  string  Returns string to extract day from a date.
	 *
	 * @since   1.0
	 */
	public function day($date)
	{
		return 'EXTRACT (DAY FROM ' . $date . ')';
	}

	/**
	 * Used to get a string to extract hour from date column.
	 *
	 * Usage:
	 * $query->select($query->hour($query->quoteName('dateColumn')));
	 *
	 * @param   string  $date  Date column containing hour to be extracted.
	 *
	 * @return  string  Returns string to extract hour from a date.
	 *
	 * @since   1.0
	 */
	public function hour($date)
	{
		return 'EXTRACT (HOUR FROM ' . $date . ')';
	}

	/**
	 * Used to get a string to extract minute from date column.
	 *
	 * Usage:
	 * $query->select($query->minute($query->quoteName('dateColumn')));
	 *
	 * @param   string  $date  Date column containing minute to be extracted.
	 *
	 * @return  string  Returns string to extract minute from a date.
	 *
	 * @since   1.0
	 */
	public function minute($date)
	{
		return 'EXTRACT (MINUTE FROM ' . $date . ')';
	}

	/**
	 * Used to get a string to extract seconds from date column.
	 *
	 * Usage:
	 * $query->select($query->second($query->quoteName('dateColumn')));
	 *
	 * @param   string  $date  Date column containing second to be extracted.
	 *
	 * @return  string  Returns string to extract second from a date.
	 *
	 * @since   1.0
	 */
	public function second($date)
	{
		return 'EXTRACT (SECOND FROM ' . $date . ')';
	}

	/**
	 * Sets the NOWAIT lock on select's output row
	 *
	 * @return  PostgresqlQuery  NOWAIT query element
	 *
	 * @since   1.0
	 */
	public function noWait()
	{
		$this->type = 'noWait';

		if ( is_null($this->noWait) )
		{
			$this->noWait = new QueryElement('NOWAIT', null);
		}

		return $this;
	}

	/**
	 * Set the LIMIT clause to the query
	 *
	 * @param   integer  $limit  Number of rows to return
	 *
	 * @return  PostgresqlQuery  Returns this object to allow chaining.
	 *
	 * @since   1.0
	 */
	public function limit($limit = 0)
	{
		if (is_null($this->limit))
		{
			$this->limit = new QueryElement('LIMIT', (int) $limit);
		}

		return $this;
	}

	/**
	 * Set the OFFSET clause to the query
	 *
	 * @param   integer  $offset  An integer for skipping rows
	 *
	 * @return  PostgresqlQuery  Returns this object to allow chaining.
	 *
	 * @since   1.0
	 */
	public function offset($offset = 0)
	{
		if (is_null($this->offset))
		{
			$this->offset = new QueryElement('OFFSET', (int) $offset);
		}

		return $this;
	}

	/**
	 * Add the RETURNING element to INSERT INTO statement.
	 *
	 * @param   mixed  $pkCol  The name of the primary key column.
	 *
	 * @return  PostgresqlQuery  Returns this object to allow chaining.
	 *
	 * @since   1.0
	 */
	public function returning($pkCol)
	{
		if (is_null($this->returning))
		{
			$this->returning = new QueryElement('RETURNING', $pkCol);
		}

		return $this;
	}

	/**
	 * Sets the offset and limit for the result set, if the database driver supports it.
	 *
	 * Usage:
	 * $query->setLimit(100, 0); (retrieve 100 rows, starting at first record)
	 * $query->setLimit(50, 50); (retrieve 50 rows, starting at 50th record)
	 *
	 * @param   integer  $limit   The limit for the result set
	 * @param   integer  $offset  The offset for the result set
	 *
	 * @return  PostgresqlQuery  Returns this object to allow chaining.
	 *
	 * @since   1.0
	 */
	public function setLimit($limit = 0, $offset = 0)
	{
		$this->limit  = (int) $limit;
		$this->offset = (int) $offset;

		return $this;
	}

	/**
	 * Method to modify a query already in string format with the needed
	 * additions to make the query limited to a particular number of
	 * results, or start at a particular offset.
	 *
	 * @param   string   $query   The query in string format
	 * @param   integer  $limit   The limit for the result set
	 * @param   integer  $offset  The offset for the result set
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function processLimit($query, $limit, $offset = 0)
	{
		if ($limit > 0)
		{
			$query .= ' LIMIT ' . $limit;
		}

		if ($offset > 0)
		{
			$query .= ' OFFSET ' . $offset;
		}

		return $query;
	}
}
