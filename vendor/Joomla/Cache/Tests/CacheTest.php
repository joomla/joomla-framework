<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

require_once __DIR__ . '/Stubs/Concrete.php';

/**
 * Tests for the Joomla\Cache\Cache class.
 *
 * @since  1.0
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Cache\Cache
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the get method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the getOption method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::getOption
	 * @since   1.0
	 */
	public function testGetOption()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the remove method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::remove
	 * @since   1.0
	 */
	public function testrRemove()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the setOption method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::setOption
	 * @since   1.0
	 */
	public function testSetOption()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the store method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::store
	 * @since   1.0
	 */
	public function teststore()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new ConcreteCache;
	}
}
