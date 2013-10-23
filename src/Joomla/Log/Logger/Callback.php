<?php
/**
 * Part of the Joomla Framework Log Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

use Joomla\Log\LogEntry;
use Joomla\Log\AbstractLogger;

/**
 * Joomla! Callback Log class
 *
 * This class allows logging to be handled by a callback function.
 * This allows unprecedented flexibility in the way logging can be handled.
 *
 * @since  1.0
 */
class Callback extends AbstractLogger
{
	/**
	 * @var    callable  The function to call when an entry is added - should return True on success
	 * @since  1.0
	 */
	protected $callback;

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 * @throws  \Exception
	 */
	public function __construct(array &$options)
	{
		// Call the parent constructor.
		parent::__construct($options);

		// Throw an exception if there is not a valid callback
		if (isset($this->options['callback']) && is_callable($this->options['callback']))
		{
			$this->callback = $this->options['callback'];
		}
		else
		{
			throw new \Exception(__CLASS__ . ' created without valid callback function.');
		}
	}

	/**
	 * Method to add an entry to the log.
	 *
	 * @param   LogEntry  $entry  The log entry object to add to the log.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 * @throws  \Exception
	 */
	public function addEntry(LogEntry $entry)
	{
		// Pass the log entry to the callback function
		call_user_func($this->callback, $entry);
	}
}
