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

	public function setUp()
	{
		if (!class_exists('Memcached')) {
			$this->markTestSkipped(
				'The Memcached class does not exist.'
			);
			return;
		}

		$options = $this->cacheOptions;
		if (!$options)
		{
			$options = array();
		}
		if (!is_array($options))
		{
			$options = array($options);
		}
		if (!isset($options['memcache.servers']))
		{
			$server = new \StdClass();
			$server->host = 'localhost';
			$server->port = '11211';
			$options['memcache.servers'] = array($server);
		}
		$this->cacheOptions = $options;
		$this->cacheClass = 'Joomla\\Cache\\Memcached';
		parent::setUp();
	}
}
