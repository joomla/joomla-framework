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
use Joomla\Log\Log;
use Joomla\Factory;

/**
 * Joomla MessageQueue logger class.
 *
 * This class is designed to output logs to a specific MySQL database table. Fields in this
 * table are based on the Syslog style of log output. This is designed to allow quick and
 * easy searching.
 *
 * @package     Joomla.Platform
 * @subpackage  Log
 * @since       11.1
 */
class Messagequeue extends Logger
{
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
		switch ($entry->priority)
		{
			case Log::EMERGENCY:
			case Log::ALERT:
			case Log::CRITICAL:
			case Log::ERROR:
				Factory::getApplication()->enqueueMessage($entry->message, 'error');
				break;
			case Log::WARNING:
				Factory::getApplication()->enqueueMessage($entry->message, 'warning');
				break;
			case Log::NOTICE:
				Factory::getApplication()->enqueueMessage($entry->message, 'notice');
				break;
			case Log::INFO:
				Factory::getApplication()->enqueueMessage($entry->message, 'message');
				break;
			default:
				// Ignore other priorities.
				break;
		}
	}
}
