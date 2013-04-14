<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Sample callbacks for the JLog package.
 */

/**
 * Helper class for JLogLoggerCallbackTest
 *
 * @since  1.0
 */
class JLogLoggerCallbackTestHelper
{
	public static $lastEntry;

	/**
	 * Function for testing JLogLoggerCallback with a static method
	 *
	 * @param   JLogEntry  $entry  A log entry to be processed.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public static function callback01(Joomla\Log\LogEntry $entry)
	{
		self::$lastEntry = $entry;
	}

	/**
	 * Function for testing JLogLoggerCallback with an object method
	 *
	 * @param   JLogEntry  $entry  A log entry to be processed.
	 *
	 * @return  null
	 *
	 * @since   1.0
	 */
	public function callback02(Joomla\Log\LogEntry $entry)
	{
	}
}

/**
 * Function for testing JLogLoggerCallback
 *
 * @param   JLogEntry  $entry  A log entry to be processed.
 *
 * @return  null
 *
 * @since   1.0
 */
function jLogLoggerCallbackTestHelperFunction(Joomla\Log\LogEntry $entry)
{
}
