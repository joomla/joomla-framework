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
 * Memcached cache driver for the Joomla Framework.
 *
 * @since  1.0
 */
class Memcached extends Cache
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
		parent::__construct($options);

		if (!extension_loaded('memcached') || !class_exists('Memcached'))
		{
			throw new \RuntimeException('Memcached not supported.');
		}
	}

	/**
	 * This will wipe out the entire cache's keys
	 *
	 * @return  boolean  The result of the clear operation.
	 *
	 * @since   1.0
	 */
	public function clear()
	{
		return $this->driver->flush();
	}

	/**
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  CacheItemInterface
	 *
	 * @since   1.0
	 */
	public function get($key)
	{
		$this->connect();
		$value = $this->driver->get($key);
		$code = $this->driver->getResultCode();
		$item = new Item($key);

		if ($code === \Memcached::RES_SUCCESS)
		{
			$item->setValue($value);
		}

		return $item;
	}

	/**
	 * Method to remove a storage entry for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function remove($key)
	{
		$this->connect();

		$this->driver->delete($key);

		if ($this->driver->getResultCode() != \Memcached::RES_SUCCESS || $this->driver->getResultCode() != \Memcached::RES_NOTFOUND)
		{
// 			throw new \RuntimeException(sprintf('Unable to remove cache entry for %s. Error message `%s`.', $key, $this->driver->getResultMessage()));
			return false;
		}

		return true;
	}

	/**
	 * Method to set a value for a storage entry.
	 *
	 * @param   string   $key    The storage entry identifier.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function set($key, $value, $ttl = null)
	{
		$this->connect();

		$this->driver->set($key, $value, $ttl);

		return (bool) ($this->driver->getResultCode() == \Memcached::RES_SUCCESS);
	}

	/**
	 * Method to determine whether a storage entry has been set for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	protected function exists($key)
	{
		$this->connect();

		$this->driver->get($key);

		return ($this->driver->getResultCode() != \Memcached::RES_NOTFOUND);
	}

	/**
	 * Connect to the Memcached servers if the connection does not already exist.
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

		$pool = $this->options['memcache.pool'];

		if ($pool)
		{
			$this->driver = new \Memcached($pool);
		}
		else
		{
			$this->driver = new \Memcached;
		}

		$this->driver->setOption(\Memcached::OPT_COMPRESSION, $this->options['memcache.compress'] ?: false);
		$this->driver->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
		$serverList = $this->driver->getServerList();

		// If we are using a persistent pool we don't want to add the servers again.
		if (empty($serverList))
		{
			$servers = $this->options['memcache.servers'] ?: array();

			foreach ($servers as $server)
			{
				$this->driver->addServer($server->host, $server->port);
			}
		}
	}
}
