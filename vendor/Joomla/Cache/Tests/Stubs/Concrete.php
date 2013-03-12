<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache\Cache;

/**
 * Tests for the Joomla\Cache\Cache class.
 *
 * @since  1.0
 */
class ConcreteCache extends Cache
{
	/**
	 * @var unknown
	 */
	public $do;

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
		return false;
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
	protected function doDelete($key)
	{
		$this->do = 'doDelete-' . $key;
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
	protected function doGet($key)
	{
		$this->do = 'doGet-' . $key;
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
	protected function doSet($key, $value, $ttl = null)
	{
		$this->do = "doSet-$key-$value-" . (int) $ttl;
	}
}
