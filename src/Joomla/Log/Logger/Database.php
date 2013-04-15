<?php
/**
 * Part of the Joomla Framework Logger Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

use Joomla\Database\DatabaseDriver;
use Joomla\Log\AbstractLogger;
use Joomla\Log\LogEntry;
use Joomla\Factory;

/**
 * Joomla! Database Log class
 *
 * This class is designed to output logs to a specific database table. Fields in this
 * table are based on the Syslog style of log output. This is designed to allow quick and
 * easy searching.
 *
 * @since  1.0
 */
class Database extends AbstractLogger
{
	/**
	 * @var    string  The name of the database driver to use for connecting to the database.
	 * @since  1.0
	 */
	protected $driver = 'mysqli';

	/**
	 * @var    string  The host name (or IP) of the server with which to connect for the logger.
	 * @since  1.0
	 */
	protected $host = '127.0.0.1';

	/**
	 * @var    string  The database server user to connect as for the logger.
	 * @since  1.0
	 */
	protected $user = 'root';

	/**
	 * @var    string  The password to use for connecting to the database server.
	 * @since  1.0
	 */
	protected $password = '';

	/**
	 * @var    string  The name of the database table to use for the logger.
	 * @since  1.0
	 */
	protected $database = 'logging';

	/**
	 * @var    string  The database table to use for logging entries.
	 * @since  1.0
	 */
	protected $table = 'jos_';

	/**
	 * @var    DatabaseDriver  The database driver object for the logger.
	 * @since  1.0
	 */
	protected $dbo;

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 */
	public function __construct(array &$options)
	{
		// Call the parent constructor.
		parent::__construct($options);

		// If both the database object and driver options are empty we want to use the system database connection.
		if (empty($this->options['db_driver']))
		{
			$this->dbo = Factory::getDBO();
			$this->driver = null;
			$this->host = null;
			$this->user = null;
			$this->password = null;
			$this->database = null;
			$this->prefix = null;
		}
		else
		{
			$this->dbo = null;
			$this->driver = (empty($this->options['db_driver'])) ? 'mysqli' : $this->options['db_driver'];
			$this->host = (empty($this->options['db_host'])) ? '127.0.0.1' : $this->options['db_host'];
			$this->user = (empty($this->options['db_user'])) ? 'root' : $this->options['db_user'];
			$this->password = (empty($this->options['db_pass'])) ? '' : $this->options['db_pass'];
			$this->database = (empty($this->options['db_database'])) ? 'logging' : $this->options['db_database'];
			$this->prefix = (empty($this->options['db_prefix'])) ? 'jos_' : $this->options['db_prefix'];
		}

		// The table name is independent of how we arrived at the connection object.
		$this->table = (empty($this->options['db_table'])) ? '#__log_entries' : $this->options['db_table'];
	}

	/**
	 * Method to add an entry to the log.
	 *
	 * @param   LogEntry  $entry  The log entry object to add to the log.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function addEntry(LogEntry $entry)
	{
		// Connect to the database if not connected.
		if (empty($this->dbo))
		{
			$this->connect();
		}

		// Convert the date.
		$entry->date = $entry->date->format($this->dbo->getDateFormat());

		$this->dbo->insertObject($this->table, $entry);
	}

	/**
	 * Method to connect to the database server based on object properties.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function connect()
	{
		// Build the configuration object to use for JDatabaseDriver.
		$options = array(
			'driver' => $this->driver,
			'host' => $this->host,
			'user' => $this->user,
			'password' => $this->password,
			'database' => $this->database,
			'prefix' => $this->prefix);

		$db = DatabaseDriver::getInstance($options);

		// Assign the database connector to the class.
		$this->dbo = $db;
	}
}
