<?php
/**
 * Part of the Joomla Framework Logger Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Logger;

use Joomla\Log\Logger;
use Joomla\Log\Entry;
use Joomla\Log\Log;

/**
 * Joomla! Syslog Log class
 *
 * This class is designed to call the PHP Syslog function call which is then sent to the
 * system wide log system. For Linux/Unix based systems this is the syslog subsystem, for
 * the Windows based implementations this can be found in the Event Log. For Windows,
 * permissions may prevent PHP from properly outputting messages.
 *
 * @since  1.0
 */
class Syslog extends Logger
{
	/**
	 * @var array Translation array for JLogEntry priorities to SysLog priority names.
	 * @since 11.1
	 */
	protected $priorities = array(
		Log::EMERGENCY => 'EMERG',
		Log::ALERT => 'ALERT',
		Log::CRITICAL => 'CRIT',
		Log::ERROR => 'ERR',
		Log::WARNING => 'WARNING',
		Log::NOTICE => 'NOTICE',
		Log::INFO => 'INFO',
		Log::DEBUG => 'DEBUG');

	/**
	 * Constructor.
	 *
	 * @param   array  &$options  Log object options.
	 *
	 * @since   1.0
	 */
	public function __construct(array &$options)
	{
		// Call the parent constructor.
		parent::__construct($options);

		// Ensure that we have an identity string for the Syslog entries.
		if (empty($this->options['sys_ident']))
		{
			$this->options['sys_ident'] = 'Joomla Framework';
		}

		// If the option to add the process id to Syslog entries is set use it, otherwise default to true.
		if (isset($this->options['sys_add_pid']))
		{
			$this->options['sys_add_pid'] = (bool) $this->options['sys_add_pid'];
		}
		else
		{
			$this->options['sys_add_pid'] = true;
		}

		// If the option to also send Syslog entries to STDERR is set use it, otherwise default to false.
		if (isset($this->options['sys_use_stderr']))
		{
			$this->options['sys_use_stderr'] = (bool) $this->options['sys_use_stderr'];
		}
		else
		{
			$this->options['sys_use_stderr'] = false;
		}

		// Build the Syslog options from our log object options.
		$sysOptions = 0;

		if ($this->options['sys_add_pid'])
		{
			$sysOptions = $sysOptions | LOG_PID;
		}

		if ($this->options['sys_use_stderr'])
		{
			$sysOptions = $sysOptions | LOG_PERROR;
		}

		// Default logging facility is LOG_USER for Windows compatibility.
		$sysFacility = LOG_USER;

		// If we have a facility passed in and we're not on Windows, reset it.
		if (isset($this->options['sys_facility']) && !defined('PHP_WINDOWS_VERSION_MAJOR'))
		{
			$sysFacility = $this->options['sys_facility'];
		}

		// Open the Syslog connection.
		openlog((string) $this->options['sys_ident'], $sysOptions, $sysFacility);
	}

	/**
	 * Destructor.
	 *
	 * @since   1.0
	 */
	public function __destruct()
	{
		closelog();
	}

	/**
	 * Method to add an entry to the log.
	 *
	 * @param   Entry  $entry  The log entry object to add to the log.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function addEntry(Entry $entry)
	{
		// Generate the value for the priority based on predefined constants.
		$priority = constant(strtoupper('LOG_' . $this->priorities[$entry->priority]));

		// Send the entry to Syslog.
		syslog($priority, '[' . $entry->category . '] ' . $entry->message);
	}
}
