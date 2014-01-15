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
	static protected $className = '\\Joomla\\Cache\\Memcached';

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
	 * Tests the Joomla\Cache\Cache::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\Cache::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->assertInstanceOf(static::$instance, '\\Joomla\\Cache\\Memcached', 'Checking Interface of class . '.get_class(static::$instance));

		parent::testSet();
	}
}
