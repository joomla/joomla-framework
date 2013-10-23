<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests\Logger;

use Joomla\Log\LogEntry;

/**
 * Helper class for Joomla\Log\Tests\Logger\CallbackTest
 *
 * @since  1.0
 */
class CallbackHelper
{
	public static $lastEntry;

	/**
	 * Function for testing Joomla\Log\Logger\Callback with a static method
	 *
	 * @param   LogEntry  $entry  A log entry to be processed.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public static function callback01(LogEntry $entry)
	{
		self::$lastEntry = $entry;
	}

	/**
	 * Function for testing Joomla\Log\Logger\Callback with an object method
	 *
	 * @param   LogEntry  $entry  A log entry to be processed.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function callback02(LogEntry $entry)
	{
	}
}
