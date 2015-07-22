<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Date\Tests;

use Joomla\Date\Date;

/**
 * Tests for Date class.
 *
 * @since  1.0
 */
class DateTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the class to test.
	 *
	 * @var    Joomla\Date\Date
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Test cases for __construct
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTest__construct()
	{
		date_default_timezone_set('UTC');

		return array(
			'basic' => array(
				'12/23/2008 13:45',
				null,
				'Tue 12/23/2008 13:45',
			),
			'unix' => array(
				strtotime('12/26/2008 13:45'),
				null,
				'Fri 12/26/2008 13:45',
			),
			'tzCT' => array(
				'12/23/2008 13:45',
				'US/Central',
				'Tue 12/23/2008 13:45',
			),
			"tzCT" => array(
				'12/23/2008 13:45',
				'US/Central',
				'Tue 12/23/2008 13:45',
			),
			'DateTime tzCT' => array(
				'12/23/2008 13:45',
				new \DateTimeZone('US/Central'),
				'Tue 12/23/2008 13:45',
			),
		);
	}

	/**
	 * Test cases for __get
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTest__get()
	{
		return array(
			'daysinmonth' => array(
				'2000-01-02 03:04:05',
				'daysinmonth',
				31,
			),
			'dayofweek' => array(
				'2000-01-02 03:04:05',
				'dayofweek',
				7,
			),
			'dayofyear' => array(
				'2000-01-02 03:04:05',
				'dayofyear',
				1,
			),
			'isleapyear' => array(
				'2000-01-02 03:04:05',
				'isleapyear',
				true,
			),
			'day' => array(
				'2000-01-02 03:04:05',
				'day',
				2,
			),
			'hour' => array(
				'2000-01-02 03:04:05',
				'hour',
				3,
			),
			'minute' => array(
				'2000-01-02 03:04:05',
				'minute',
				4,
			),
			'second' => array(
				'2000-01-02 03:04:05',
				'second',
				5,
			),
			'month' => array(
				'2000-01-02 03:04:05',
				'month',
				1,
			),
			'ordinal' => array(
				'2000-01-02 03:04:05',
				'ordinal',
				'nd',
			),
			'week' => array(
				'2000-01-02 03:04:05',
				'week',
				52,
			),
			'year' => array(
				'2000-01-02 03:04:05',
				'year',
				2000,
			),
		);
	}

	/**
	 * Test cases for format
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestFormat()
	{
		return array(
			'basic' => array(
				'd/m/Y H:i:s',
				true,
				'20/12/2007 11:44:56',
			),
			'mmddyy' => array(
				'mdy His',
				true,
				'122007 114456',
			),
			'mmddyyGMT' => array(
				'mdy His',
				false,
				'122007 164456',
			),
			'Long' => array(
				'D F j, Y H:i:s',
				true,
				'Thu December 20, 2007 11:44:56',
				),
			'LongGMT' => array(
				'D F j, Y H:i:s',
				false,
				'Thu December 20, 2007 16:44:56',
				),
			'Long2' => array(
				'H:i:s D F j, Y',
				false,
				'16:44:56 Thu December 20, 2007',
				),
			'Long3' => array(
				'H:i:s l F j, Y',
				false,
				'16:44:56 Thursday December 20, 2007',
			),
			'Long4' => array(
				'H:i:s l M j, Y',
				false,
				'16:44:56 Thursday Dec 20, 2007',
			),
			'RFC822' => array(
				'r',
				false,
				'Thu, 20 Dec 2007 16:44:56 +0000',
			),
		);
	}

	/**
	 * Test cases for getOffsetFromGMT
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestGetOffsetFromGmt()
	{
		return array(
			'basic' => array(
				null,
				'2007-11-20 11:44:56',
				null,
				0,
			),
			'Atlantic/Azores' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				null,
				-3600,
			),
			'	/Hours' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				true,
				-1,
			),
			'Australia/Brisbane' => array(
				'Australia/Brisbane',
				'2007-5-20 11:44:56',
				null,
				36000,
			),
			'Australia/Brisbane/Hours' => array(
				'Australia/Brisbane',
				'2007-5-20 11:44:56',
				true,
				10,
			),
		);
	}

	/**
	 * Test cases for setTimezone
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestSetTimezone()
	{
		return array(
			'New_York' => array(
				'America/New_York',
				'Thu, 20 Dec 2007 11:44:56 -0500',
			),
			'Chicago' => array(
				'America/Chicago',
				'Thu, 20 Dec 2007 10:44:56 -0600',
			),
			'Los_Angeles' => array(
				'America/Los_Angeles',
				'Thu, 20 Dec 2007 08:44:56 -0800',
			),
			'Isle of Man' => array(
				'Europe/Isle_of_Man',
				'Thu, 20 Dec 2007 16:44:56 +0000',
			),
			'Berlin' => array(
				'Europe/Berlin',
				'Thu, 20 Dec 2007 17:44:56 +0100',
			),
			'Pacific/Port_Moresby' => array(
				'Pacific/Port_Moresby',
				'Fri, 21 Dec 2007 02:44:56 +1000',
			),
		);
	}

	/**
	 * Test cases for toISO8601
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestToISO8601()
	{
		return array(
			'basic' => array(
				null,
				'2007-11-20 11:44:56',
				null,
				'2007-11-20T11:44:56+00:00',
			),
			'Atlantic/AzoresGMT' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				null,
				'2007-11-20T12:44:56+00:00',
			),
			'Atlantic/AzoresLocal' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				true,
				'2007-11-20T11:44:56-01:00',
			),
			'Australia/BrisbaneGMT' => array(
				'Australia/Brisbane',
				'2007-5-20 11:44:56',
				null,
				'2007-05-20T01:44:56+00:00',
			),
			'Australia/Brisbane/Local' => array(
				'Australia/Brisbane',
				'2007-5-20 11:44:56',
				true,
				'2007-05-20T11:44:56+10:00',
			),
		);
	}

	/**
	 * Test cases for toRFC822
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestToRFC822()
	{
		return array(
			'basic' => array(
				null,
				'2007-11-22 11:44:56',
				null,
				'Thu, 22 Nov 2007 11:44:56 +0000',
			),
			'Atlantic/AzoresGMT' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				null,
				'Tue, 20 Nov 2007 12:44:56 +0000',
			),
			'Atlantic/AzoresLocal' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				true,
				'Tue, 20 Nov 2007 11:44:56 -0100',
			),
			'Australia/BrisbaneGMT' => array(
				'Australia/Brisbane',
				'2007-5-22 11:44:56',
				null,
				'Tue, 22 May 2007 01:44:56 +0000',
			),
			'Australia/Brisbane/Local' => array(
				'Australia/Brisbane',
				'2007-5-23 11:44:56',
				true,
				'Wed, 23 May 2007 11:44:56 +1000',
			),
		);
	}

	/**
	 * Test cases for __toString
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestToString()
	{
		return array(
			'basic' => array(
				null,
				'2007-12-20 11:44:56',
			),
			'mmmddyy' => array(
				'mdy His',
				'122007 114456',
			),
			'Long' => array(
				'D F j, Y H:i:s',
				'Thu December 20, 2007 11:44:56',
			),
		);
	}

	/**
	 * Test cases for toUnix
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedTestToUnix()
	{
		return array(
			'basic' => array(
				null,
				'2007-11-20 11:44:56',
				1195559096,
			),
			'Atlantic/AzoresGMT' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				1195562696,
			),
			'Atlantic/AzoresLocal' => array(
				'Atlantic/Azores',
				'2007-11-20 11:44:56',
				1195562696,
			),
			'Australia/BrisbaneGMT' => array(
				'Australia/Brisbane',
				'2007-5-20 11:44:56',
				1179625496,
			),
			'Australia/Brisbane/Local' => array(
				'Australia/Brisbane',
				'2007-5-20 11:44:56',
				1179625496,
			),
		);
	}

	/**
	 * Testing the Constructor
	 *
	 * @param   string  $date          What time should be set?
	 * @param   mixed   $tz            Which time zone? (can be string or numeric
	 * @param   string  $expectedTime  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider  seedTest__construct
	 * @since   1.0
	 * @covers  Joomla\Date\Date::__construct
	 */
	public function test__construct($date, $tz, $expectedTime)
	{
		$Date = new Date($date, $tz);

		$this->assertThat(
			date_format($Date, 'D m/d/Y H:i'),
			$this->equalTo($expectedTime)
		);

		$this->assertThat(
			$Date->format('D m/d/Y H:i', true),
			$this->equalTo($expectedTime)
		);
	}

	/**
	 * Testing the Constructor
	 *
	 * @param   string  $date      The date.
	 * @param   string  $property  The property to test.
	 * @param   string  $expected  The expected value.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedTest__get
	 * @since   1.0
	 * @covers  Joomla\Date\Date::__get
	 */
	public function test__get($date, $property, $expected)
	{
		$Date = new Date($date);

		$this->assertThat(
			$Date->$property,
			$this->equalTo($expected)
		);
	}

	/**
	 * Tests the magic __toString method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @covers  Joomla\Date\Date::__toString
	 */
	public function test__toString()
	{
		Date::$format = 'Y-m-d H:i:s';

		$Date = new Date('2000-01-01 00:00:00');

		$this->assertThat(
			(string) $Date,
			$this->equalTo('2000-01-01 00:00:00')
		);
	}

	/**
	 * Testing toString
	 *
	 * @param   string  $format        How should the time be formatted?
	 * @param   string  $expectedTime  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestToString
	 * @since   1.0
	 * @covers  Joomla\Date\Date::__toString
	 */
	public function testToString($format, $expectedTime)
	{
		if (!is_null($format))
		{
			Date::$format = $format;
		}

		$this->assertThat(
			$this->instance->__toString(),
			$this->equalTo($expectedTime)
		);
	}

	/**
	 * Testing getOffsetFromGMT
	 *
	 * @param   mixed    $tz        Which time zone? (can be string or numeric
	 * @param   string   $setTime   What time should be set?
	 * @param   boolean  $hours     Return offset in hours (true) or seconds?
	 * @param   string   $expected  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestGetOffsetFromGMT
	 * @since   1.0
	 * @covers  Joomla\Date\Date::getOffsetFromGMT
	 */
	public function testGetOffsetFromGmt($tz, $setTime, $hours, $expected)
	{
		if (is_null($tz))
		{
			$testDate = new Date($setTime);
		}
		else
		{
			$testDate = new Date($setTime, $tz);
		}

		if (is_null($hours))
		{
			$offset = $testDate->getOffsetFromGmt();
		}
		else
		{
			$offset = $testDate->getOffsetFromGmt($hours);
		}

		$this->assertThat($offset, $this->equalTo($expected));
	}

	/**
	 * Testing format
	 *
	 * @param   string   $format    How should the time be formatted?
	 * @param   boolean  $local     Local (true) or GMT?
	 * @param   string   $expected  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestFormat
	 * @since   1.0
	 * @covers  Joomla\Date\Date::format
	 */
	public function testFormat($format, $local, $expected)
	{
		$this->assertThat(
			$this->instance->format($format, $local),
			$this->equalTo($expected)
		);
	}

	/**
	 * Testing toRFC822
	 *
	 * @param   mixed   $tz        Which time zone? (can be string or numeric
	 * @param   string  $setTime   What time should be set?
	 * @param   bool    $local     Local (true) or GMT?
	 * @param   string  $expected  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestToRFC822
	 * @since   1.0
	 * @covers  Joomla\Date\Date::toRFC822
	 */
	public function testToRFC822($tz, $setTime, $local, $expected)
	{
		if (is_null($tz))
		{
			$testDate = new Date($setTime);
		}
		else
		{
			$testDate = new Date($setTime, $tz);
		}

		$this->assertThat(
			$testDate->toRFC822($local),
			$this->equalTo($expected)
		);
	}

	/**
	 * Testing toISO8601
	 *
	 * @param   mixed    $tz        Which time zone? (can be string or numeric
	 * @param   string   $setTime   What time should be set?
	 * @param   boolean  $local     Local (true) or GMT?
	 * @param   string   $expected  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestToISO8601
	 * @since   1.0
	 * @covers  Joomla\Date\Date::toISO8601
	 */
	public function testToISO8601($tz, $setTime, $local, $expected)
	{
		if (is_null($tz))
		{
			$testDate = new Date($setTime);
		}
		else
		{
			$testDate = new Date($setTime, $tz);
		}

		$this->assertThat(
			$testDate->toISO8601($local),
			$this->equalTo($expected)
		);
	}

	/**
	 * Testing toUnix
	 *
	 * @param   mixed   $tz        Which time zone? (can be string or numeric
	 * @param   string  $setTime   What time should be set?
	 * @param   string  $expected  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestToUnix
	 * @since   1.0
	 * @covers  Joomla\Date\Date::toUnix
	 */
	public function testToUnix($tz, $setTime, $expected)
	{
		if (is_null($tz))
		{
			$testDate = new Date($setTime);
		}
		else
		{
			$testDate = new Date($setTime, $tz);
		}

		$this->assertThat(
			$testDate->toUnix(),
			$this->equalTo($expected)
		);
	}

	/**
	 * Testing setTimezone
	 *
	 * @param   string  $tz        Which Time Zone should it be?
	 * @param   string  $expected  What should the resulting time string look like?
	 *
	 * @return  void
	 *
	 * @dataProvider seedTestSetTimezone
	 * @since   1.0
	 * @covers  Joomla\Date\Date::setTimezone
	 */
	public function testSetTimezone($tz, $expected)
	{
		$this->instance->setTimezone(new \DateTimeZone($tz));
		$this->assertThat(
			$this->instance->format('r', true),
			$this->equalTo($expected)
		);
	}

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		// Note: do not extend parent setUp method

		$this->instance = new Date('12/20/2007 11:44:56', 'America/New_York');
	}
}
