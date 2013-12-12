<?php
/**
 * Part of the Joomla Framework Cache Package
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * Google App Engine Memcached cache driver for the Joomla Framework.
 *
 * @since  1.0
 */
class Gaememcached extends Memcached
{
	/**
	 * @var    \Memcached  The memcached driver.
	 * @since  1.0
	 */
	private $driver;

	/**
	 * Constructor.
	 *
	 * @param   mixed  $options  An options array, or an object that implements \ArrayAccess
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct($options = array())
	{
		Cache::__construct($options);

		if (!class_exists('Memcached') ||
			!isset($_SERVER['SERVER_SOFTWARE'])
			|| (strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false)
		)
		{
			throw new \RuntimeException('Google App Engine Memcached not supported.');
		}
	}


	/**
	 * Connect to the Google App Engine Memcached server if the connection does not already exist.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function connect()
	{
		// We want to only create the driver once.
		if (isset($this->driver))
		{
			return;
		}

		$this->driver = new \Memcached;

		$this->driver->setOption(\Memcached::OPT_COMPRESSION, $this->options['memcache.compress'] ?: false);
		$this->driver->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

	}
}
