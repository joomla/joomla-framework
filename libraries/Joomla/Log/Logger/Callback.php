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

use Joomla\Language\Text;
use Joomla\Log\Entry;
use Joomla\Log\Logger;
use Exception;

/**
 * Joomla! Callback Log class
 *
 * This class allows logging to be handled by a callback function.
 * This allows unprecedented flexibility in the way logging can be handled.
 *
 * @package     Joomla.Platform
 * @subpackage  Log
 * @since       12.2
 */
class Callback extends Logger
{
	/**
	 * @var    callable  The function to call when an entry is added - should return True on success
	 * @since  12.2
	 */
	protected $callback;

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   12.2
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
			throw new Exception(Text::_('JLogLoggerCallback created without valid callback function.'));
		}
	}

	/**
	 * Method to add an entry to the log.
	 *
	 * @param   Entry  $entry  The log entry object to add to the log.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function addEntry(Entry $entry)
	{
		// Pass the log entry to the callback function
		call_user_func($this->callback, $entry);
	}
}
