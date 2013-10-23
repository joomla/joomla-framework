<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests;

use Joomla\Log\LogEntry;
use Joomla\Log\Log;
use Joomla\Date\Date;

/**
 * Test class for Joomla\Log\LogEntry.
 *
 * @since  1.0
 */
class LogEntryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Verify the default values for the log entry object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @covers  Joomla\Log\LogEntry::__construct
	 */
	public function testDefaultValues()
	{
		$tmp = new LogEntry('Lorem ipsum dolor sit amet');
		$date = new Date('now');

		// Message.
		$this->assertThat(
			$tmp->message,
			$this->equalTo('Lorem ipsum dolor sit amet'),
			'Line: ' . __LINE__ . '.'
		);

		// Priority.
		$this->assertThat(
			$tmp->priority,
			$this->equalTo(Log::INFO),
			'Line: ' . __LINE__ . '.'
		);

		// Category.
		$this->assertThat(
			$tmp->category,
			$this->equalTo(''),
			'Line: ' . __LINE__ . '.'
		);

		// Date.
		$this->assertThat(
			$tmp->date->toISO8601(),
			$this->equalTo($date->toISO8601()),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Verify the priority for the entry object cannot be something not in the approved list.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @covers  Joomla\Log\LogEntry::__construct
	 */
	public function testBadPriorityValues()
	{
		$tmp = new LogEntry('Lorem ipsum dolor sit amet', Log::ALL);
		$this->assertThat(
			$tmp->priority,
			$this->equalTo(Log::INFO),
			'Line: ' . __LINE__ . '.'
		);

		$tmp = new LogEntry('Lorem ipsum dolor sit amet', 23642872);
		$this->assertThat(
			$tmp->priority,
			$this->equalTo(Log::INFO),
			'Line: ' . __LINE__ . '.'
		);

		$tmp = new LogEntry('Lorem ipsum dolor sit amet', 'foobar');
		$this->assertThat(
			$tmp->priority,
			$this->equalTo(Log::INFO),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test that non-standard category values are sanitized.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @covers  Joomla\Log\LogEntry::__construct
	 */
	public function testCategorySanitization()
	{
		// Category should always be lowercase.
		$tmp = new LogEntry('Lorem ipsum dolor sit amet', Log::INFO, 'TestingTheCategory');
		$this->assertThat(
			$tmp->category,
			$this->equalTo('testingthecategory'),
			'Line: ' . __LINE__ . '.'
		);

		// Category should not have spaces.
		$tmp = new LogEntry('Lorem ipsum dolor sit amet', Log::INFO, 'testing the category');
		$this->assertThat(
			$tmp->category,
			$this->equalTo('testingthecategory'),
			'Line: ' . __LINE__ . '.'
		);

		// Category should not have special characters.
		$tmp = new LogEntry('Lorem ipsum dolor sit amet', Log::INFO, 'testing@#$^the*&@^#*&category');
		$this->assertThat(
			$tmp->category,
			$this->equalTo('testingthecategory'),
			'Line: ' . __LINE__ . '.'
		);

		// Category should allow numbers.
		$tmp = new LogEntry('Lorem ipsum dolor sit amet', Log::INFO, 'testing1the2category');
		$this->assertThat(
			$tmp->category,
			$this->equalTo('testing1the2category'),
			'Line: ' . __LINE__ . '.'
		);
	}
}
