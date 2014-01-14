<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Session\Tests\Storage;

use Joomla\Session\Tests\StorageTest;
use Joomla\Session\Storage\Memcached as StorageMemcached;
use Joomla\Session\Storage;

/**
 * Test class for Joomla\Session\Storage\Memcached.
 *
 * @since  1.0
 */
class MemcachedTest extends StorageTest
{

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		// Skip these tests if Memcache isn't available.
		if (!StorageMemcached::isSupported())
		{
			$this->markTestSkipped('Memcached storage is not enabled on this system.');
		}

		// Create the caching object
		static::$object = Storage::getInstance('Memcached');

		// Parent contains the rest of the setup
		parent::setUp();

	}

}
