<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache;

use Joomla\Registry\Registry;

/**
 * Joomla! Caching Class
 *
 * @package  Joomla\Framework
 * @since    1.0
 */
abstract class Cache
{
	/**
	 * @var    array  An array of key/value pairs to be used as a runtime cache.
	 * @since  1.0
	 */
	static protected $runtime = array();

	/**
	 * @var    JRegistry  The options for the cache object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry  $options  Caching options object.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct(Registry $options = null)
	{
		// Set the options object.
		$this->options = $options ? $options : new Registry;

		$this->options->def('ttl', 900);
		$this->options->def('runtime', true);
	}

	/**
	 * Get cached data by id.  If the cached data has expired then the cached data will be removed
	 * and false will be returned.
	 *
	 * @param   string   $cacheId       The cache data id.
	 * @param   boolean  $checkRuntime  True to check runtime cache first.
	 *
	 * @return  mixed  Cached data string if it exists.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function get($cacheId, $checkRuntime = true)
	{
		if ($checkRuntime && isset(self::$runtime[$cacheId]) && $this->options->get('runtime'))
		{
			return self::$runtime[$cacheId];
		}

		$data = $this->fetch($cacheId);

		if ($this->options->get('runtime'))
		{
			self::$runtime[$cacheId] = $data;
		}

		return $data;
	}

	/**
	 * Get an option from the JCache instance.
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
	 * Remove a cached data entry by id.
	 *
	 * @param   string  $cacheId  The cache data id.
	 *
	 * @return  JCache  This object for method chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function remove($cacheId)
	{
		$this->delete($cacheId);

		if ($this->options->get('runtime'))
		{
			unset(self::$runtime[$cacheId]);
		}

		return $this;
	}

	/**
	 * Set an option for the JCache instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  JCache  This object for method chaining.
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
	 * @param   string  $cacheId  The cache data id
	 * @param   mixed   $data     The data to store
	 *
	 * @return  JCache  This object for method chaining.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function store($cacheId, $data)
	{
		if ($this->exists($cacheId))
		{
			$this->set($cacheId, $data, $this->options->get('ttl'));
		}
		else
		{
			$this->add($cacheId, $data, $this->options->get('ttl'));
		}

		if ($this->options->get('runtime'))
		{
			self::$runtime[$cacheId] = $data;
		}

		return $this;
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
	abstract protected function add($key, $value, $ttl);

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
	abstract protected function delete($key);

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
	abstract protected function fetch($key);

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
	abstract protected function set($key, $value, $ttl);
}
