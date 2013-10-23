<?php
/**
 * Part of the Joomla Framework Log Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log;

use Joomla\Date\Date;

/**
 * Joomla! Log Entry class
 *
 * This class is designed to hold log entries for either writing to an engine, or for
 * supported engines, retrieving lists and building in memory (PHP based) search operations.
 *
 * @since  1.0
 */
class LogEntry
{
	/**
	 * Application responsible for log entry.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $category;

	/**
	 * The date the message was logged.
	 *
	 * @var    Date
	 * @since  1.0
	 */
	public $date;

	/**
	 * Message to be logged.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $message;

	/**
	 * The priority of the message to be logged.
	 *
	 * @var    string
	 * @since  1.0
	 * @see    $priorities
	 */
	public $priority = Log::INFO;

	/**
	 * List of available log priority levels [Based on the Syslog default levels].
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $priorities = array(
		Log::EMERGENCY,
		Log::ALERT,
		Log::CRITICAL,
		Log::ERROR,
		Log::WARNING,
		Log::NOTICE,
		Log::INFO,
		Log::DEBUG
	);

	/**
	 * Constructor
	 *
	 * @param   string  $message   The message to log.
	 * @param   string  $priority  Message priority based on {$this->priorities}.
	 * @param   string  $category  Type of entry
	 * @param   string  $date      Date of entry (defaults to now if not specified or blank)
	 *
	 * @since   1.0
	 */
	public function __construct($message, $priority = Log::INFO, $category = '', $date = null)
	{
		$this->message = (string) $message;

		// Sanitize the priority.
		if (!in_array($priority, $this->priorities, true))
		{
			$priority = Log::INFO;
		}

		$this->priority = $priority;

		// Sanitize category if it exists.
		if (!empty($category))
		{
			$this->category = (string) strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $category));
		}

		// Get the date as a Date object.
		$this->date = new Date($date ? $date : 'now');
	}
}
