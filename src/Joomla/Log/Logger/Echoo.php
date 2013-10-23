<?php
/**
 * Part of the Joomla Framework Log Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

use Joomla\Log\AbstractLogger;
use Joomla\Log\LogEntry;

/**
 * Joomla Echo logger class.
 *
 * @since  1.0
 */
class Echoo extends AbstractLogger
{
	/**
	 * @var    string  Value to use at the end of an echoed log entry to separate lines.
	 * @since  1.0
	 */
	protected $line_separator = "\n";

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 */
	public function __construct(array &$options)
	{
		parent::__construct($options);

		if (!empty($this->options['line_separator']))
		{
			$this->line_separator = $this->options['line_separator'];
		}
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
		echo $this->priorities[$entry->priority] . ': '
			. $entry->message . (empty($entry->category) ? '' : ' [' . $entry->category . ']')
			. $this->line_separator;
	}
}
