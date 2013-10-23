<?php
/**
 * Part of the Joomla Framework Log Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log;

/**
 * Joomla! Logger Base Class
 *
 * This class is used to be the basis of logger classes to allow for defined functions
 * to exist regardless of the child class.
 *
 * @since  1.0
 */
abstract class AbstractLogger
{
	/**
	 * Options array for the Log instance.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $options = array();

	/**
	 * @var    array  Translation array for LogEntry priorities to text strings.
	 * @since  1.0
	 */
	protected $priorities = array(
		Log::EMERGENCY => 'EMERGENCY',
		Log::ALERT => 'ALERT',
		Log::CRITICAL => 'CRITICAL',
		Log::ERROR => 'ERROR',
		Log::WARNING => 'WARNING',
		Log::NOTICE => 'NOTICE',
		Log::INFO => 'INFO',
		Log::DEBUG => 'DEBUG');

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 */
	public function __construct(array &$options)
	{
		// Set the options for the class.
		$this->options = & $options;
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
	abstract public function addEntry(LogEntry $entry);
}
