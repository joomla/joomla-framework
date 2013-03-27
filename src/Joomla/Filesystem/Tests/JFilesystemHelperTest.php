<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Filesystem\Helper;

/**
 * Test class for Helper.
 *
 * @since  1.0
 */
class FilesystemHelperTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Helper
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Helper;
	}

	/**
	 * Test...
	 *
	 * @todo Implement testRemotefsize().
	 *
	 * @return void
	 */
	public function testRemotefsize()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testFtpChmod().
	 *
	 * @return void
	 */
	public function testFtpChmod()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGetWriteModes().
	 *
	 * @return void
	 */
	public function testGetWriteModes()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGetSupported().
	 *
	 * @return void
	 */
	public function testGetSupported()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGetTransports().
	 *
	 * @return void
	 */
	public function testGetTransports()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGetFilters().
	 *
	 * @return void
	 */
	public function testGetFilters()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers  Joomla\Filesystem\Helper::getJStreams
	 *
	 * @return void
	 */
	public function testGetJStreams()
	{
		$streams = Helper::getJStreams();

		$this->assertEquals(
			array('String'),
			$streams
		);
	}

	/**
	 * Test...
	 *
	 * @covers  Joomla\Filesystem\Helper::isJoomlaStream
	 *
	 * @return void
	 */
	public function testIsJoomlaStream()
	{
		$this->assertTrue(
			Helper::isJoomlaStream('String')
		);

		$this->assertFalse(
			Helper::isJoomlaStream('unknown')
		);
	}
}
