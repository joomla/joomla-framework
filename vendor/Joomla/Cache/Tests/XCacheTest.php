<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\XCache class.
 *
 * @since  1.0
 */
class XCacheTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Cache\XCache
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the Joomla\Cache\XCache::clear method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\XCache::clear
	 * @since   1.0
	 */
	public function testClear()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\XCache::exists method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\XCache::exists
	 * @since   1.0
	 */
	public function testExists()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\XCache::get method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\XCache::get
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\XCache::remove method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\XCache::remove
	 * @since   1.0
	 */
	public function testRemove()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\XCache::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\XCache::set
	 * @since   1.0
	 */
	public function testSet()
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

		try
		{
			$this->instance = new Cache\XCache;
		}
		catch (\Exception $e)
		{
			$this->markTestSkipped();
		}
	}
}
