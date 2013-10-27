<?php
/**
 * Part of the Joomla Framework Cache Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Psr\Cache\CacheInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Joomla! Caching Class
 *
 * @since  1.0
 */
abstract class Cache implements CacheInterface
{
	/**
	 * @var    \ArrayAccess  The options for the cache object.
	 * @since  1.0
	 */
	protected $options;

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
		if ($options instanceof \ArrayAccess || is_array($options))
		{
			// Set a default ttl if none is set in the options.
			if (!isset($options['ttl']))
			{
				$options['ttl'] = 900;
			}

			$this->options = $options;
		}
		else
		{
			throw new \RuntimeException(sprintf('%s requires an options array or an object that implements \\ArrayAccess', __CLASS__));
		}
	}

	/**
	 * This will wipe out the entire cache's keys
	 *
	 * @return  boolean  The result of the clear operation.
	 *
	 * @since   1.0
	 */
	abstract public function clear();

	/**
	 * Get cached data by id.  If the cached data has expired then the cached data will be removed
	 * and false will be returned.
	 *
	 * @param   string  $key  The cache data id.
	 *
	 * @return  CacheItemInterface  Cached data string if it exists.
	 *
	 * @since   1.0
	 */
	abstract public function get($key);

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
		$result = array();

		foreach ($keys as $key)
		{
			$result[$key] = $this->get($key);
		}

		return $result;
	}

	/**
	 * Get an option from the Cache instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Delete a cached data entry by id.
	 *
	 * @param   string  $key  The cache data id.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	abstract public function remove($key);

	/**
	 * Remove multiple cache items in a single operation.
	 *
	 * @param   array  $keys  The array of keys to be removed.
	 *
	 * @return  array  An associative array of 'key' => result, elements. Each array row has the key being deleted
	 *                 and the result of that operation. The result will be a boolean of true or false
	 *                 representing if the cache item was removed or not
	 *
	 * @since   1.0
	 */
	public function removeMultiple($keys)
	{
		$result = array();

		foreach ($keys as $key)
		{
			$result[$key] = $this->remove($key);
		}

		return $result;
	}

	/**
	 * Store the cached data by id.
	 *
	 * @param   string   $key   The cache data id
	 * @param   mixed    $data  The data to store
	 * @param   integer  $ttl   The number of seconds before the stored data expires.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	abstract public function set($key, $data, $ttl = null);

	/**
	 * Persisting a set of key => value pairs in the cache, with an optional TTL.
	 *
	 * @param   array         $items  An array of key => value pairs for a multiple-set operation.
	 * @param   null|integer  $ttl    Optional. The TTL value of this item. If no value is sent and the driver supports TTL
	 *                                then the library may set a default value for it or let the driver take care of that.
	 *
	 * @return  boolean  The result of the multiple-set operation.
	 *
	 * @since   1.0
	 */
	public function setMultiple($items, $ttl = null)
	{
		foreach ($items as $key => $value)
		{
			$this->set($key, $value, $ttl);
		}

		return true;
	}

	/**
	 * Set an option for the Cache instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Cache  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
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
	abstract protected function exists($key);
}
