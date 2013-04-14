<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler\Tests;

use Joomla\Profiler\ProfilePoint;
use Joomla\Test\TestHelper;

/**
 * Tests for the ProfilePoint class.
 *
 * @since  1.0
 */
class ProfilePointTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the constructor.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\ProfilePoint::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$point = new ProfilePoint('test');
		$this->assertEquals('test', TestHelper::getValue($point, 'name'));
		$this->assertSame(0.0, TestHelper::getValue($point, 'time'));
		$this->assertSame(0, TestHelper::getValue($point, 'memoryBytes'));

		$point = new ProfilePoint('foo', '1', '1048576');
		$this->assertEquals('foo', TestHelper::getValue($point, 'name'));
		$this->assertSame(1.0, TestHelper::getValue($point, 'time'));
		$this->assertSame(1048576, TestHelper::getValue($point, 'memoryBytes'));
	}

	/**
	 * Tests the getName method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\ProfilePoint::getName
	 * @since   1.0
	 */
	public function testGetName()
	{
		$profilePoint = new ProfilePoint('test');
		$this->assertEquals($profilePoint->getName(), 'test');
	}

	/**
	 * Tests the getTime method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\ProfilePoint::getTime
	 * @since   1.0
	 */
	public function testGetTime()
	{
		$profilePoint = new ProfilePoint('test', 0, 0);
		$this->assertEquals($profilePoint->getTime(), 0);

		$profilePoint = new ProfilePoint('test', 1.5, 0);
		$this->assertEquals($profilePoint->getTime(), 1.5);
	}

	/**
	 * Tests the getMemoryBytes method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\ProfilePoint::getMemoryBytes
	 * @since   1.0
	 */
	public function testGetMemoryBytes()
	{
		$profilePoint = new ProfilePoint('test', 0, 0);
		$this->assertEquals($profilePoint->getMemoryBytes(), 0);

		$profilePoint = new ProfilePoint('test', 0, 1048576);
		$this->assertEquals($profilePoint->getMemoryBytes(), 1048576);
	}

	/**
	 * Tests the getMemoryMegaBytes method.
	 *
	 * @return  void
	 *
	 * @covers  \Joomla\Profiler\ProfilePoint::getMemoryMegaBytes
	 * @since   1.0
	 */
	public function testGetMemoryMegaBytes()
	{
		$profilePoint = new ProfilePoint('test', 0, 0);
		$this->assertEquals($profilePoint->getMemoryMegaBytes(), 0);

		$profilePoint = new ProfilePoint('test', 0, 1048576);
		$this->assertEquals($profilePoint->getMemoryMegaBytes(), 1);
	}
}
