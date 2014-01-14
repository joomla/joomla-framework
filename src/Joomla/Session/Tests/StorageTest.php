<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Session\Tests;

/**
 * Test class for Joomla\Session\Storage.
 *
 * @since  1.0
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Session\Storage
	 * @since  1.0
	 */
	protected static $object;

	/**
	 * @var    String  key to use in cache
	 * @since  1.1
	 */
	protected static $key;

	/**
	 * @var    String  default value to store in cache
	 * @since  1.1
	 */
	protected static $value;

	/**
	 * @var    String  name for session
	 * @since  1.1
	 */
	protected static $sessionName;

	/**
	 * @var    String  path for session
	 * @since  1.1
	 */
	protected static $sessionPath;




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
		if (empty(static::$object))
		{
			$this->markTestSkipped('There is no caching engine.');
		}

		$key = md5(date(DATE_RFC2822));
		$value = 'Test value';
		static::$key = $key;
		static::$value = $value;
		static::$sessionName = 'SessionName';
		static::$sessionPath = 'SessionPath';

		parent::setUp();
	}

	/**
	 * Test getInstance
	 *
	 * @todo Implement testGetInstance().
	 *
	 * @return void
	 */
	public function testGetInstance()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}


	/**
	 * Test...
	 *
	 * @todo Implement test__Construct().
	 *
	 * @return void
	 */
	public function test__Construct()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}


	/**
	 * Test...
	 *
	 * @todo Implement testRegister().
	 *
	 * @return void
	 */
	public function testRegister()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test session open
	 *
	 *
	 *
	 * @return void
	 */
	public function testOpen()
	{
		$this->assertThat(static::$object->open(static::$sessionPath, static::$sessionName), $this->isTrue(), __LINE__);
	}

	/**
	 * Test close session
	 *
	 *
	 *
	 * @return void
	 */
	public function testClose()
	{
		static::$object->open(static::$sessionPath, static::$sessionName);
		$this->assertThat(static::$object->close(), $this->isTrue(), __LINE__);
	}

	/**
	 * Test read default key and value
	 *
	 *
	 *
	 * @return void
	 */
	public function testRead()
	{
		static::$object->write(static::$key, static::$value);
		$this->assertThat(static::$object->read(static::$key), $this->equalTo(static::$value), __LINE__);
	}

	/**
	 * Test write nothing default key and value
	 *
	 *
	 *
	 * @return void
	 */
	public function testWrite()
	{
		$this->assertThat(static::$object->write(static::$key, static::$value), $this->isTrue(), __LINE__);
	}

	/**
	 * Test storage destroy no value
	 *
	 *
	 *
	 * @return void
	 */
	public function testDestroy()
	{
		// Create the key/value
		static::$object->write(static::$key, static::$value);
		$this->assertThat(static::$object->destroy(static::$key), $this->isTrue(), __LINE__);
	}

	/**
	 * Test garbage collection
	 *
	 *
	 * @return void
	 */
	public function testGc()
	{
		$this->assertThat(static::$object->gc(), $this->isTrue(), __LINE__);
	}

	/**
	 * Test isSupported
	 *
	 * @todo Implement testIsSupported().
	 *
	 * @return void
	 */
	public function testIsSupported()
	{
		$this->assertThat(\Joomla\Session\Storage::isSupported(), $this->isTrue(), __LINE__);
	}
}
