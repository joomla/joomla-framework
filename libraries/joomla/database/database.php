<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database;

defined('JPATH_PLATFORM') or die;

use Joomla\Log\Log;
use Joomla\Language\Text;

/**
 * Database connector class.
 *
 * @package     Joomla.Platform
 * @subpackage  Database
 * @since       11.1
 * @deprecated  13.3
 */
abstract class Database
{
	/**
	 * Gets the error message from the database connection.
	 *
	 * @param   boolean  $escaped  True to escape the message string for use in JavaScript.
	 *
	 * @return  string  The error message for the most recent query.
	 *
	 * @deprecated  13.3
	 * @since   11.1
	 */
	public function getErrorMsg($escaped = false)
	{
		Log::add('JDatabase::getErrorMsg() is deprecated, use exception handling instead.', Log::WARNING, 'deprecated');

		if ($escaped)
		{
			return addslashes($this->errorMsg);
		}
		else
		{
			return $this->errorMsg;
		}
	}

	/**
	 * Gets the error number from the database connection.
	 *
	 * @return      integer  The error number for the most recent query.
	 *
	 * @since       11.1
	 * @deprecated  13.3
	 */
	public function getErrorNum()
	{
		Log::add('JDatabase::getErrorNum() is deprecated, use exception handling instead.', Log::WARNING, 'deprecated');

		return $this->errorNum;
	}

	/**
	 * Return the most recent error message for the database connector.
	 *
	 * @param   boolean  $showSQL  True to display the SQL statement sent to the database as well as the error.
	 *
	 * @return  string  The error message for the most recent query.
	 *
	 * @since   11.1
	 * @deprecated  13.3
	 */
	public function stderr($showSQL = false)
	{
		Log::add('JDatabase::stderr() is deprecated.', Log::WARNING, 'deprecated');

		if ($this->errorNum != 0)
		{
			return Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $this->errorNum, $this->errorMsg)
			. ($showSQL ? "<br />SQL = <pre>$this->sql</pre>" : '');
		}
		else
		{
			return Text::_('JLIB_DATABASE_FUNCTION_NOERROR');
		}
	}
}
