<?php
/**
 * @package     Joomla\Framework
 * @subpackage  Log
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log;


/**
 * Joomla! Logger Base Class
 *
 * This class is used to be the basis of logger classes to allow for defined functions
 * to exist regardless of the child class.
 *
 * @package     Joomla\Framework
 * @subpackage  Log
 * @since       12.2
 */
abstract class Logger
{
	/**
	 * Options array for the JLog instance.
	 * @var    array
	 * @since  12.2
	 */
	protected $options = array();

	/**
	 * @var    array  Translation array for JLogEntry priorities to text strings.
	 * @since  12.2
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
	 * @since   12.2
	 */
	public function __construct(array &$options)
	{
		// Set the options for the class.
		$this->options = & $options;
	}

	/**
	 * Method to add an entry to the log.
	 *
	 * @param   Entry  $entry  The log entry object to add to the log.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	abstract public function addEntry(Entry $entry);
}
