<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests;

use Joomla\Log\Log;
use Joomla\Log\LogEntry;

/**
 * LogInspector class.
 *
 * @since  1.0
 */
class LogInspector extends Log
{
	public $configurations;

	public $loggers;

	public $lookup;

	public $queue = array();

	/**
	 * Constructor.
	 *
	 * @since   1.0
	 */
	public function __construct()
	{
		return parent::__construct();
	}

	/**
	 * Clear the static global instance
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function clearInstance()
	{
		static::$instance = null;
	}

	/**
	 * Method to add an entry to the appropriate loggers.
	 *
	 * @param   LogEntry  $entry  The entry to add.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function addLogEntry(LogEntry $entry)
	{
		$this->queue[] = $entry;

		parent::addLogEntry($entry);
	}

	/**
	 * Method to find the loggers to use based on priority and category values.
	 *
	 * @param   integer  $priority  Message priority.
	 * @param   string   $category  Type of entry
	 *
	 * @return  array  The array of loggers to use for the given priority and category values.
	 *
	 * @since   1.0
	 */
	public function findLoggers($priority, $category)
	{
		return parent::findLoggers($priority, $category);
	}
}
