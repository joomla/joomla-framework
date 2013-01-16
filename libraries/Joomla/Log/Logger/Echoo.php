<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Log
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

defined('JPATH_PLATFORM') or die;

use Joomla\Log\Logger;
use Joomla\Log\Entry;

/**
 * Joomla Echo logger class.
 *
 * @package     Joomla.Platform
 * @subpackage  Log
 * @since       11.1
 */
class Echoo extends Logger
{
	/**
	 * @var    string  Value to use at the end of an echoed log entry to separate lines.
	 * @since  11.1
	 */
	protected $line_separator = "\n";

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   12.1
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
	 * @param   Entry  $entry  The log entry object to add to the log.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function addEntry(Entry $entry)
	{
		echo $this->priorities[$entry->priority] . ': '
			. $entry->message . (empty($entry->category) ? '' : ' [' . $entry->category . ']')
			. $this->line_separator;
	}
}
