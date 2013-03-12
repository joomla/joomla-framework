<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Joomla\Registry\Registry;

/**
 * Joomla! Caching Class
 *
 * @since  1.0
 */
abstract class Cache
{
	/**
	 * @var    Registry  The options for the cache object.
	 * @since  1.0
	 */
	protected $options;

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
		// Set the options object.
		$this->options = $options ? $options : new Registry;

		$this->options->def('ttl', 900);
	}

	/**
	 * Delete a cached data entry by id.
	 *
	 * @param   string  $cacheId  The cache data id.
	 *
	 * @return  Cache  This object for method chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function delete($cacheId)
	{
		$this->doDelete($cacheId);

		return $this;
	}

	/**
	 * Get cached data by id.  If the cached data has expired then the cached data will be removed
	 * and false will be returned.
	 *
	 * @param   string  $cacheId  The cache data id.
	 *
	 * @return  mixed  Cached data string if it exists.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function get($cacheId)
	{
		$data = $this->doGet($cacheId);

		return $data;
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
		return $this->options->get($key);
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
		$this->options->set($key, $value);

		return $this;
	}

	/**
	 * Store the cached data by id.
	 *
	 * @param   string   $cacheId  The cache data id
	 * @param   mixed    $data     The data to store
	 * @param   integer  $ttl      The number of seconds before the stored data expires.
	 *
	 * @return  Cache  This object for method chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function set($cacheId, $data, $ttl = null)
	{
		$this->doSet($cacheId, $data, $ttl ?: $this->options->get('ttl'));

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
	abstract protected function doDelete($key);

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
	abstract protected function doGet($key);

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
	abstract protected function doSet($key, $value, $ttl = null);
}
