<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler;

/**
 * Utility class to assist in the process of benchmarking the execution
 * of sections of code to understand where time is being spent.
 *
 * @package  Joomla\Framework
 * @since    1.0
 */
class Profiler
{
	/**
	 * The start time.
	 *
	 * @var    integer
	 * @since  1.0
	 */
	protected $start = 0;

	/**
	 * The prefix to use in the output.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $prefix = '';

	/**
	 * The buffer of profiling messages.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $buffer = null;

	/**
	 * @var    float
	 * @since  1.0
	 */
	protected $previousTime = 0.0;

	/**
	 * @var    float
	 * @since  1.0
	 */
	protected $previousMem = 0.0;

	/**
	 * Profiler instances container.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $instances = array();

	/**
	 * Constructor
	 *
	 * @param   string  $prefix  Prefix for mark messages
	 *
	 * @since  1.0
	 */
	protected function __construct($prefix = '')
	{
		$this->start = $this->getmicrotime();
		$this->prefix = $prefix;
		$this->buffer = array();
	}

	/**
	 * Returns the global Profiler object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param   string  $prefix  Prefix used to distinguish profiler objects.
	 *
	 * @return  Profiler  The Profiler object.
	 *
	 * @since   1.0
	 */
	public static function getInstance($prefix = '')
	{
		if (empty(self::$instances[$prefix]))
		{
			self::$instances[$prefix] = new self($prefix);
		}

		return self::$instances[$prefix];
	}

	/**
	 * Output a time mark
	 *
	 * The mark is returned as text enclosed in <div> tags
	 * with a CSS class of 'profiler'.
	 *
	 * @param   string  $label  A label for the time mark
	 *
	 * @return  string  Mark enclosed in <div> tags
	 *
	 * @since   1.0
	 */
	public function mark($label)
	{
		$current = self::getmicrotime() - $this->start;
		$currentMem = 0;

		$currentMem = memory_get_usage() / 1048576;
		$mark = sprintf(
			'<code>%s %.3f seconds (+%.3f); %0.2f MB (%s%0.3f) - %s</code>',
			$this->prefix,
			$current,
			$current - $this->previousTime,
			$currentMem,
			($currentMem > $this->previousMem) ? '+' : '', $currentMem - $this->previousMem,
			$label
		);

		$this->previousTime = $current;
		$this->previousMem = $currentMem;
		$this->buffer[] = $mark;

		return $mark;
	}

	/**
	 * Get the current time.
	 *
	 * @return  float The current time
	 *
	 * @since   1.0
	 */
	public static function getmicrotime()
	{
		list ($usec, $sec) = explode(' ', microtime());

		return ((float) $usec + (float) $sec);
	}

	/**
	 * Get all profiler marks.
	 *
	 * Returns an array of all marks created since the Profiler object
	 * was instantiated.  Marks are strings as per {@link Profiler::mark()}.
	 *
	 * @return  array  Array of profiler marks
	 */
	public function getBuffer()
	{
		return $this->buffer;
	}
}
