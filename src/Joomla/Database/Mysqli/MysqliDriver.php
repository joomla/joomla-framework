<?php
/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Mysqli;

use Joomla\Database\DatabaseDriver;
use Psr\Log;

/**
 * MySQLi Database Driver
 *
 * @see    http://php.net/manual/en/book.mysqli.php
 * @since  1.0
 */
class MysqliDriver extends DatabaseDriver
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $name = 'mysqli';

	/**
	 * The character(s) used to quote SQL statement names such as table names or field names,
	 * etc. The child classes should define this as necessary.  If a single character string the
	 * same character is used for both sides of the quoted name, else the first character will be
	 * used for the opening quote and the second for the closing quote.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $nameQuote = '`';

	/**
	 * The null or zero representation of a timestamp for the database driver.  This should be
	 * defined in child classes to hold the appropriate value for the engine.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $nullDate = '0000-00-00 00:00:00';

	/**
	 * The minimum supported database version.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected static $dbMinimum = '5.0.4';

	/**
	 * Constructor.
	 *
	 * @param   array  $options  List of options used to configure the connection
	 *
	 * @since   1.0
	 */
	public function __construct($options)
	{
		// Get some basic values from the options.
		$options['host'] = (isset($options['host'])) ? $options['host'] : 'localhost';
		$options['user'] = (isset($options['user'])) ? $options['user'] : 'root';
		$options['password'] = (isset($options['password'])) ? $options['password'] : '';
		$options['database'] = (isset($options['database'])) ? $options['database'] : '';
		$options['select'] = (isset($options['select'])) ? (bool) $options['select'] : true;
		$options['port'] = null;
		$options['socket'] = null;

		// Finalize initialisation.
		parent::__construct($options);
	}

	/**
	 * Destructor.
	 *
	 * @since   1.0
	 */
	public function __destruct()
	{
		if (is_callable(array($this->connection, 'close')))
		{
			mysqli_close($this->connection);
		}
	}

	/**
	 * Connects to the database if needed.
	 *
	 * @return  void  Returns void if the database connected successfully.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function connect()
	{
		if ($this->connection)
		{
			return;
		}

		/*
		 * Unlike mysql_connect(), mysqli_connect() takes the port and socket as separate arguments. Therefore, we
		 * have to extract them from the host string.
		 */
		$port = isset($this->options['port']) ? $this->options['port'] : 3306;

		if (preg_match(
			'/^(?P<host>((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(:(?P<port>.+))?$/',
			$this->options['host'],
			$matches
			))
		{
			// It's an IPv4 address with or without port
			$this->options['host'] = $matches['host'];

			if (!empty($matches['port']))
			{
				$port = $matches['port'];
			}
		}
		elseif (preg_match('/^(?P<host>\[.*\])(:(?P<port>.+))?$/', $this->options['host'], $matches))
		{
			// We assume square-bracketed IPv6 address with or without port, e.g. [fe80:102::2%eth1]:3306
			$this->options['host'] = $matches['host'];

			if (!empty($matches['port']))
			{
				$port = $matches['port'];
			}
		}
		elseif (preg_match('/^(?P<host>(\w+:\/{2,3})?[a-z0-9\.\-]+)(:(?P<port>[^:]+))?$/i', $this->options['host'], $matches))
		{
			// Named host (e.g example.com or localhost) with or without port
			$this->options['host'] = $matches['host'];

			if (!empty($matches['port']))
			{
				$port = $matches['port'];
			}
		}
		elseif (preg_match('/^:(?P<port>[^:]+)$/', $this->options['host'], $matches))
		{
			// Empty host, just port, e.g. ':3306'
			$this->options['host'] = 'localhost';
			$port = $matches['port'];
		}

		// ... else we assume normal (naked) IPv6 address, so host and port stay as they are or default

		// Get the port number or socket name
		if (is_numeric($port))
		{
			$this->options['port'] = (int) $port;
		}
		else
		{
			$this->options['socket'] = $port;
		}

		// Make sure the MySQLi extension for PHP is installed and enabled.
		if (!static::isSupported())
		{
			throw new \RuntimeException('The MySQLi extension is not available');
		}

		$this->connection = @mysqli_connect(
			$this->options['host'], $this->options['user'], $this->options['password'], null, $this->options['port'], $this->options['socket']
		);

		// Attempt to connect to the server.
		if (!$this->connection)
		{
			throw new \RuntimeException('Could not connect to MySQL.');
		}

		// Set sql_mode to non_strict mode
		mysqli_query($this->connection, "SET @@SESSION.sql_mode = '';");

		// If auto-select is enabled select the given database.
		if ($this->options['select'] && !empty($this->options['database']))
		{
			$this->select($this->options['database']);
		}

		// Set charactersets (needed for MySQL 4.1.2+).
		$this->setUTF();
	}

	/**
	 * Disconnects the database.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function disconnect()
	{
		// Close the connection.
		if (is_callable($this->connection, 'close'))
		{
			mysqli_close($this->connection);
		}

		$this->connection = null;
	}

	/**
	 * Method to escape a string for usage in an SQL statement.
	 *
	 * @param   string   $text   The string to be escaped.
	 * @param   boolean  $extra  Optional parameter to provide extra escaping.
	 *
	 * @return  string  The escaped string.
	 *
	 * @since   1.0
	 */
	public function escape($text, $extra = false)
	{
		$this->connect();

		$result = mysqli_real_escape_string($this->getConnection(), $text);

		if ($extra)
		{
			$result = addcslashes($result, '%_');
		}

		return $result;
	}

	/**
	 * Test to see if the MySQLi connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.0
	 */
	public static function isSupported()
	{
		return (function_exists('mysqli_connect'));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return  boolean  True if connected to the database engine.
	 *
	 * @since   1.0
	 */
	public function connected()
	{
		if (is_object($this->connection))
		{
			return mysqli_ping($this->connection);
		}

		return false;
	}

	/**
	 * Drops a table from the database.
	 *
	 * @param   string   $tableName  The name of the database table to drop.
	 * @param   boolean  $ifExists   Optionally specify that the table must exist before it is dropped.
	 *
	 * @return  MysqliDriver  Returns this object to support chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function dropTable($tableName, $ifExists = true)
	{
		$this->connect();

		$this->setQuery('DROP TABLE ' . ($ifExists ? 'IF EXISTS ' : '') . $this->quoteName($tableName));

		$this->execute();

		return $this;
	}

	/**
	 * Get the number of affected rows for the previous executed SQL statement.
	 *
	 * @return  integer  The number of affected rows.
	 *
	 * @since   1.0
	 */
	public function getAffectedRows()
	{
		$this->connect();

		return mysqli_affected_rows($this->connection);
	}

	/**
	 * Method to get the database collation in use by sampling a text field of a table in the database.
	 *
	 * @return  mixed  The collation in use by the database (string) or boolean false if not supported.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getCollation()
	{
		$this->connect();

		$tables = $this->getTableList();

		$this->setQuery('SHOW FULL COLUMNS FROM ' . $tables[0]);
		$array = $this->loadAssocList();

		foreach ($array as $field)
		{
			if (!is_null($field['Collation']))
			{
				return $field['Collation'];
			}
		}

		return null;
	}

	/**
	 * Get the number of returned rows for the previous executed SQL statement.
	 *
	 * @param   resource  $cursor  An optional database cursor resource to extract the row count from.
	 *
	 * @return  integer   The number of returned rows.
	 *
	 * @since   1.0
	 */
	public function getNumRows($cursor = null)
	{
		return mysqli_num_rows($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Shows the table CREATE statement that creates the given tables.
	 *
	 * @param   mixed  $tables  A table name or a list of table names.
	 *
	 * @return  array  A list of the create SQL for the tables.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getTableCreate($tables)
	{
		$this->connect();

		$result = array();

		// Sanitize input to an array and iterate over the list.
		settype($tables, 'array');

		foreach ($tables as $table)
		{
			// Set the query to get the table CREATE statement.
			$this->setQuery('SHOW CREATE TABLE ' . $this->quoteName($this->escape($table)));
			$row = $this->loadRow();

			// Populate the result array based on the create statements.
			$result[$table] = $row[1];
		}

		return $result;
	}

	/**
	 * Retrieves field information about a given table.
	 *
	 * @param   string   $table     The name of the database table.
	 * @param   boolean  $typeOnly  True to only return field types.
	 *
	 * @return  array  An array of fields for the database table.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getTableColumns($table, $typeOnly = true)
	{
		$this->connect();

		$result = array();

		// Set the query to get the table fields statement.
		$this->setQuery('SHOW FULL COLUMNS FROM ' . $this->quoteName($this->escape($table)));
		$fields = $this->loadObjectList();

		// If we only want the type as the value add just that to the list.
		if ($typeOnly)
		{
			foreach ($fields as $field)
			{
				$result[$field->Field] = preg_replace("/[(0-9)]/", '', $field->Type);
			}
		}
		else
		// If we want the whole field data object add that to the list.
		{
			foreach ($fields as $field)
			{
				$result[$field->Field] = $field;
			}
		}

		return $result;
	}

	/**
	 * Get the details list of keys for a table.
	 *
	 * @param   string  $table  The name of the table.
	 *
	 * @return  array  An array of the column specification for the table.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getTableKeys($table)
	{
		$this->connect();

		// Get the details columns information.
		$this->setQuery('SHOW KEYS FROM ' . $this->quoteName($table));
		$keys = $this->loadObjectList();

		return $keys;
	}

	/**
	 * Method to get an array of all tables in the database.
	 *
	 * @return  array  An array of all the tables in the database.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getTableList()
	{
		$this->connect();

		// Set the query to get the tables statement.
		$this->setQuery('SHOW TABLES');
		$tables = $this->loadColumn();

		return $tables;
	}

	/**
	 * Get the version of the database connector.
	 *
	 * @return  string  The database connector version.
	 *
	 * @since   1.0
	 */
	public function getVersion()
	{
		$this->connect();

		return mysqli_get_server_info($this->connection);
	}

	/**
	 * Method to get the auto-incremented value from the last INSERT statement.
	 *
	 * @return  mixed  The value of the auto-increment field from the last inserted row.
	 *                 If the value is greater than maximal int value, it will return a string.
	 *
	 * @since   1.0
	 */
	public function insertid()
	{
		$this->connect();

		return mysqli_insert_id($this->connection);
	}

	/**
	 * Locks a table in the database.
	 *
	 * @param   string  $table  The name of the table to unlock.
	 *
	 * @return  MysqliDriver  Returns this object to support chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function lockTable($table)
	{
		$this->setQuery('LOCK TABLES ' . $this->quoteName($table) . ' WRITE')->execute();

		return $this;
	}

	/**
	 * Execute the SQL statement.
	 *
	 * @return  mixed  A database cursor resource on success, boolean false on failure.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		$this->connect();

		if (!is_object($this->connection))
		{
			$this->log(
				Log\LogLevel::ERROR,
				'Database query failed (error #{code}): {message}',
				array('code' => $this->errorNum, 'message' => $this->errorMsg)
			);
			throw new \RuntimeException($this->errorMsg, $this->errorNum);
		}

		// Take a local copy so that we don't modify the original query and cause issues later
		$sql = $this->replacePrefix((string) $this->sql);

		if ($this->limit > 0 || $this->offset > 0)
		{
			$sql .= ' LIMIT ' . $this->offset . ', ' . $this->limit;
		}

		// Increment the query counter.
		$this->count++;

		// If debugging is enabled then let's log the query.
		if ($this->debug)
		{
			// Add the query to the object queue.
			$this->log(
				Log\LogLevel::DEBUG,
				'{sql}',
				array('sql' => $sql, 'category' => 'databasequery', 'trace' => debug_backtrace())
			);
		}

		// Reset the error values.
		$this->errorNum = 0;
		$this->errorMsg = '';

		// Execute the query. Error suppression is used here to prevent warnings/notices that the connection has been lost.
		$this->cursor = @mysqli_query($this->connection, $sql);

		// If an error occurred handle it.
		if (!$this->cursor)
		{
			$this->errorNum = (int) mysqli_errno($this->connection);
			$this->errorMsg = (string) mysqli_error($this->connection) . "\n-- SQL --\n" . $sql;

			// Check if the server was disconnected.
			if (!$this->connected())
			{
				try
				{
					// Attempt to reconnect.
					$this->connection = null;
					$this->connect();
				}
				catch (\RuntimeException $e)
				// If connect fails, ignore that exception and throw the normal exception.
				{
					$this->log(
						Log\LogLevel::ERROR,
						'Database query failed (error #{code}): {message}',
						array('code' => $this->errorNum, 'message' => $this->errorMsg)
					);
					throw new \RuntimeException($this->errorMsg, $this->errorNum);
				}

				// Since we were able to reconnect, run the query again.
				return $this->execute();
			}
			else
			// The server was not disconnected.
			{
				$this->log(
					Log\LogLevel::ERROR,
					'Database query failed (error #{code}): {message}',
					array('code' => $this->errorNum, 'message' => $this->errorMsg)
				);
				throw new \RuntimeException($this->errorMsg, $this->errorNum);
			}
		}

		return $this->cursor;
	}

	/**
	 * Renames a table in the database.
	 *
	 * @param   string  $oldTable  The name of the table to be renamed
	 * @param   string  $newTable  The new name for the table.
	 * @param   string  $backup    Not used by MySQL.
	 * @param   string  $prefix    Not used by MySQL.
	 *
	 * @return  MysqliDriver  Returns this object to support chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function renameTable($oldTable, $newTable, $backup = null, $prefix = null)
	{
		$this->setQuery('RENAME TABLE ' . $oldTable . ' TO ' . $newTable)->execute();

		return $this;
	}

	/**
	 * Select a database for use.
	 *
	 * @param   string  $database  The name of the database to select for use.
	 *
	 * @return  boolean  True if the database was successfully selected.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function select($database)
	{
		$this->connect();

		if (!$database)
		{
			return false;
		}

		if (!mysqli_select_db($this->connection, $database))
		{
			throw new \RuntimeException('Could not connect to database.');
		}

		return true;
	}

	/**
	 * Set the connection to use UTF-8 character encoding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 */
	public function setUTF()
	{
		$this->connect();

		return $this->connection->set_charset('utf8');
	}

	/**
	 * Method to commit a transaction.
	 *
	 * @param   boolean  $toSavepoint  If true, commit to the last savepoint.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function transactionCommit($toSavepoint = false)
	{
		$this->connect();

		if (!$toSavepoint || $this->transactionDepth <= 1)
		{
			if ($this->setQuery('COMMIT')->execute())
			{
				$this->transactionDepth = 0;
			}

			return;
		}

		$this->transactionDepth--;
	}

	/**
	 * Method to roll back a transaction.
	 *
	 * @param   boolean  $toSavepoint  If true, rollback to the last savepoint.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function transactionRollback($toSavepoint = false)
	{
		$this->connect();

		if (!$toSavepoint || $this->transactionDepth <= 1)
		{
			if ($this->setQuery('ROLLBACK')->execute())
			{
				$this->transactionDepth = 0;
			}

			return;
		}

		$savepoint = 'SP_' . ($this->transactionDepth - 1);
		$this->setQuery('ROLLBACK TO SAVEPOINT ' . $this->quoteName($savepoint));

		if ($this->execute())
		{
			$this->transactionDepth--;
		}
	}

	/**
	 * Method to initialize a transaction.
	 *
	 * @param   boolean  $asSavepoint  If true and a transaction is already active, a savepoint will be created.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function transactionStart($asSavepoint = false)
	{
		$this->connect();

		if (!$asSavepoint || !$this->transactionDepth)
		{
			if ($this->setQuery('START TRANSACTION')->execute())
			{
				$this->transactionDepth = 1;
			}

			return;
		}

		$savepoint = 'SP_' . $this->transactionDepth;
		$this->setQuery('SAVEPOINT ' . $this->quoteName($savepoint));

		if ($this->execute())
		{
			$this->transactionDepth++;
		}
	}

	/**
	 * Method to fetch a row from the result set cursor as an array.
	 *
	 * @param   mixed  $cursor  The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 *
	 * @since   1.0
	 */
	protected function fetchArray($cursor = null)
	{
		return mysqli_fetch_row($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Method to fetch a row from the result set cursor as an associative array.
	 *
	 * @param   mixed  $cursor  The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 *
	 * @since   1.0
	 */
	protected function fetchAssoc($cursor = null)
	{
		return mysqli_fetch_assoc($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Method to fetch a row from the result set cursor as an object.
	 *
	 * @param   mixed   $cursor  The optional result set cursor from which to fetch the row.
	 * @param   string  $class   The class name to use for the returned row object.
	 *
	 * @return  mixed   Either the next row from the result set or false if there are no more rows.
	 *
	 * @since   1.0
	 */
	protected function fetchObject($cursor = null, $class = '\\stdClass')
	{
		return mysqli_fetch_object($cursor ? $cursor : $this->cursor, $class);
	}

	/**
	 * Method to free up the memory used for the result set.
	 *
	 * @param   mixed  $cursor  The optional result set cursor from which to fetch the row.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function freeResult($cursor = null)
	{
		mysqli_free_result($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Unlocks tables in the database.
	 *
	 * @return  MysqliDriver  Returns this object to support chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function unlockTables()
	{
		$this->setQuery('UNLOCK TABLES')->execute();

		return $this;
	}
}
