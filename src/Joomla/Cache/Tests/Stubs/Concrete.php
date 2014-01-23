<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache\Cache;
use Joomla\Cache\Item;
use Psr\Cache\CacheItemInterface;

/**
 * Tests for the Joomla\Cache\Cache class.
 *
 * @since  1.0
 */
class ConcreteCache extends Cache
{
	/**
	 * @var    \ArrayObject  Database of cached items,
	 * we use ArrayObject so it can be easily
	 * passed by reference
	 *
	 * @since  1.1
	 */
	protected $db;

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
		$this->db = new \ArrayObject;
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
		// Replace the db with a new blank array
		$clearData = $this->db->exchangeArray(array());
		unset($clearData);

		return true;
	}

	/**
	 * Method to generate a Cache Item from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  CacheItemInterface
	 *
	 * @since   1.0
	 */
	public function get($key)
	{
		$item = $this->getItem($key);

		return $item;
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
	public function getItem($key)
	{
		$item = new Item($key);

		try
		{
			$value = $this->getValue($key);
			$item->setValue($value);
		}
		catch ( \Exception $e)
		{
			/**
			 * Backend caching mechanisms may throw exceptions
			 * to indicate missing data.  Catch all exceptions
			 * so program flow is uninterrupted.  For misses
			 * we can safely do nothing and return the
			 * CacheItem we created since it flags itself as
			 * a miss when constructed.  Specific cache classes
			 * should override this method and deal with
			 * exceptions appropriately.
			 */
		}

		return $item;
	}

	/**
	 * Method to get a storage entry value from a key.
	 *
	 * @param   string  $key  The storage entry identifier.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function getValue($key)
	{
		try
		{
			$value = $this->db[$key];
		}
		catch ( \Exception $e)
		{
			/**
			 * Backend caching mechanisms may throw exceptions
			 * to indicate missing data.  Catch all exceptions
			 * so program flow is uninterrupted.  If the exception
			 * can be recovered from gracefully, do so.  If not
			 * re-throw the exception.  As with getItem() this
			 * logic must be added for specific cache engine
			 * test suites.
			 */
			throw $e;
		}
		return $value;
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
		$oldCache = $this->db->getArrayCopy();

		if (array_key_exists($key,$oldCache ))
		{
			$keyArray = array($key => $key );
			$newCache = array_diff_key($oldCache, $keyArray);
			$this->db->exchangeArray($newCache);

			return true;
		}

		return false;
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
		$this->db[$key] = $value;

		return true;
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
		return array_key_exists($this->db, $key);
	}
}
