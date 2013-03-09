<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Joomla\Registry\Registry;

/**
 * Memcached cache driver for the Joomla Framework.
 *
 * @package  Joomla\Framework
 * @since    1.0
 */
class Memcached extends Cache
{
	/**
	 * @var    Memcached  The memcached driver.
	 * @since  1.0
	 */
	private $driver;

	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  Caching options object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct(Registry $options = null)
	{
		parent::__construct($options);

		if (!extension_loaded('memcached') || !class_exists('\\Memcached'))
		{
			throw new \RuntimeException('Memcached not supported.');
		}
	}

	/**
	 * Method to add a storage entry.
	 *
	 * @param   string   $key    The storage entry identifier.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function add($key, $value, $ttl)
	{
		$this->_connect();

		$this->driver->add($key, $value, $ttl);

		if ($this->driver->getResultCode() != \Memcached::RES_SUCCESS)
		{
			throw new \RuntimeException(sprintf('Unable to add cache entry for %s. Error message `%s`.', $key, $this->driver->getResultMessage()));
		}
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
		$this->_connect();

		$this->driver->get($key);

		return ($this->driver->getResultCode() != \Memcached::RES_NOTFOUND);
	}

	/**
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function fetch($key)
	{
		$this->_connect();

		$data = $this->driver->get($key);

		$code = $this->driver->getResultCode();

		if ($code === \Memcached::RES_SUCCESS)
		{
			return $data;
		}
		elseif ($code === \Memcached::RES_NOTFOUND)
		{
			return null;
		}
		else
		{
			throw new \RuntimeException(sprintf('Unable to fetch cache entry for %s. Error message `%s`.', $key, $this->driver->getResultMessage()));
		}
	}

	/**
	 * Method to remove a storage entry for a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function delete($key)
	{
		$this->_connect();

		$this->driver->delete($key);

		if ($this->driver->getResultCode() != \Memcached::RES_SUCCESS || $this->driver->getResultCode() != \Memcached::RES_NOTFOUND)
		{
			throw new \RuntimeException(sprintf('Unable to remove cache entry for %s. Error message `%s`.', $key, $this->driver->getResultMessage()));
		}
	}

	/**
	 * Method to set a value for a storage entry.
	 *
	 * @param   string   $key    The storage entry identifier.
	 * @param   mixed    $value  The data to be stored.
	 * @param   integer  $ttl    The number of seconds before the stored data expires.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function set($key, $value, $ttl)
	{
		$this->_connect();

		$this->driver->set($key, $value, $ttl);

		if ($this->driver->getResultCode() != \Memcached::RES_SUCCESS)
		{
			throw new \RuntimeException(sprintf('Unable to set cache entry for %s. Error message `%s`.', $key, $this->driver->getResultMessage()));
		}
	}

	/**
	 * Connect to the Memcached servers if the connection does not already exist.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function _connect()
	{
		// We want to only create the driver once.
		if (isset($this->driver))
		{
			return;
		}

		$pool = $this->options->get('memcache.pool');

		if ($pool)
		{
			$this->driver = new \Memcached($pool);
		}
		else
		{
			$this->driver = new \Memcached;
		}

		$this->driver->setOption(\Memcached::OPT_COMPRESSION, $this->options->get('memcache.compress', false));
		$this->driver->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
		$serverList = $this->driver->getServerList();

		// If we are using a persistent pool we don't want to add the servers again.
		if (empty($serverList))
		{
			$servers = $this->options->get('memcache.servers', array());

			foreach ($servers as $server)
			{
				$this->driver->addServer($server->host, $server->port);
			}
		}
	}
}
