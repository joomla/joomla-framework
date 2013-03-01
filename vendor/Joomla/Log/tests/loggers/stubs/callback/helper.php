<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  Log
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Sample callbacks for the JLog package.
 */

/**
 * Helper class for JLogLoggerCallbackTest
 *
 * @package     Joomla\Framework\Tests
 * @subpackage  Log
 *
 * @since       12.2
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
	 * @since   12.2
	 */
	public static function callback01(Joomla\Log\Entry $entry)
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
	 * @since   12.2
	 */
	public function callback02(Joomla\Log\Entry $entry)
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
 * @since   12.2
 */
function jLogLoggerCallbackTestHelperFunction(Joomla\Log\Entry $entry)
{
}
