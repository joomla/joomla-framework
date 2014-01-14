<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;

/**
 * Tests for the Joomla\Cache\Memcached class.
 *
 * @since  1.0
 */
class MemcachedTest extends CacheTest
{


	/**
	 * Tests the Joomla\Cache\Cache::__construct method.
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
	 * Setup the tests for memcached.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{

		static::$className = '\\Joomla\\Cache\\Memcached';
		parent::setUp();

	}
}
