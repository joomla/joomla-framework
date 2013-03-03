<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler\Tests;

use Joomla\Profiler\Profiler;

/**
 * Test class for Joomla\Profiler\Profiler.
 *
 * @package  Joomla\Framework\Test
 * @since    1.0
 */
class ProfilerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * An instance of the class to test.
	 *
	 * @var    \Joomla\Profiler\Profiler
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the mark method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testMark()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the getMircotime method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetmicrotime()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the getMemory method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetMemory()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the getBuffer method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetBuffer()
	{
		$this->markTestIncomplete();
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
		parent::setUp();

		$this->instance = Profiler::getInstance();
	}
}
