<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Inspector classes for the JLog package.
 */

use Joomla\Log\Log;
use Joomla\Log\LogEntry;

/**
 * JLogInspector class.
 *
 * @since  1.0
 */
class JLogInspector extends Log
{
	public $configurations;

	public $loggers;

	public $lookup;

	public $queue = array();

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		return parent::__construct();
	}

	/**
	 * Test...
	 *
	 * @return void
	 */
	public static function clearInstance()
	{
		JLog::$instance = null;
	}

	/**
	 * Test...
	 *
	 * @param   Entry  $entry  The entry to add.
	 *
	 * @return void
	 */
	public function addLogEntry(LogEntry $entry)
	{
		$this->queue[] = $entry;

		return parent::addLogEntry($entry);
	}

	/**
	 * Test...
	 *
	 * @param   int     $priority  Priority.
	 * @param   string  $category  Category.
	 *
	 * @return void
	 */
	public function findLoggers($priority, $category)
	{
		return parent::findLoggers($priority, $category);
	}
}
