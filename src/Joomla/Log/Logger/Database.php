<?php
/**
 * Part of the Joomla Framework Log Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

use Joomla\Database\DatabaseDriver;
use Joomla\Log\AbstractLogger;
use Joomla\Log\LogEntry;

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
	 * @var    DatabaseDriver  The database driver object for the logger.
	 * @since  1.0
	 */
	protected $db;

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct(array &$options)
	{
		// Call the parent constructor.
		parent::__construct($options);

		// If we're missing the db object, or it's not an instance of DatabaseDriver, throw an exception.
		if (!isset($this->options['db']) || !($this->options['db'] instanceof DatabaseDriver))
		{
			throw new \RuntimeException(
				sprintf('%s requires a `db` option that is an instance of Joomla\\Database\\DatabaseDriver.', __CLASS__)
			);
		}

		$this->db = $this->options['db'];

		// The table name is independent of how we arrived at the connection object.
		$this->table = empty($this->options['db_table']) ? '#__log_entries' : $this->options['db_table'];
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
		// Convert the date.
		$entry->date = $entry->date->format($this->db->getDateFormat());

		$this->db->insertObject($this->table, $entry);
	}
}
