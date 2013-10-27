<?php
/**
 * Part of the Joomla Framework Cache Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * APC cache driver for the Joomla Framework.
 *
 * @since  1.0
 */
class Apc extends Cache
{
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

		if (!extension_loaded('apc') || !is_callable('apc_fetch'))
		{
			throw new \RuntimeException('APC not supported.');
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
		return apc_clear_cache('user');
	}

	/**
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  CacheItemInterface
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function get($key)
	{
		$success = false;
		$value = apc_fetch($key, $success);
		$item = new Item($key);

		if ($success)
		{
			$item->setValue($value);
		}

		return $item;
	}

	/**
	 * Obtain multiple CacheItems by their unique keys.
	 *
	 * @param   array  $keys  A list of keys that can obtained in a single operation.
	 *
	 * @return  array  An associative array of CacheItem objects keyed on the cache key.
	 *
	 * @since   1.0
	 */
	public function getMultiple($keys)
	{
		$items = array();
		$success = false;
		$values = apc_fetch($keys, $success);

		if ($success && is_array($values))
		{
			foreach ($values as $key => $value)
			{
				// @todo - identify the value when a cache item is not found.
				$items[$key] = new Item($key);
				$items[$key]->setValue($value);
			}
		}

		return $items;
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
		return apc_delete($key);
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
		return apc_store($key, $value, $ttl ?: $this->options['ttl']);
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
		return apc_exists($key);
	}
}
