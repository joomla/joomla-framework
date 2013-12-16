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

		/**
		 *  Google App Engine Server Software is either
		 * Development or
		 * Google App Engine
		 */
		if ( !isset($_SERVER['SERVER_SOFTWARE']) )
		{
			throw new \RuntimeException('This script is not running on Google App Engine.');
		}

		$beginsWith = substr($_SERVER['SERVER_SOFTWARE'],11);
		if ( ($beginsWith == 'Development') ||
			( $beginsWith == 'Google App '))
		{
			// Gaememcached is supported if the Memcached class exists
			throw new \RuntimeException('This script is not running on Google App Engine.');
		}

		// Make sure Memcached exists
		if ( !class_exists('Memcached'))
		{
			throw new \RuntimeException('Google App Engine Memcached class does not exist.');
		}

		// Skip Memcached parent because memcached extension check will fail
		Cache::__construct($options);
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

		// Set Memcached options
		$this->driver->setOption(\Memcached::OPT_COMPRESSION, $this->options['memcache.compress'] ?: false);
		$this->driver->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
	}
}