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
 * Cache item instance for the Joomla Framework.
 *
 * @since  1.0
 */
class Item implements CacheItemInterface
{
	/**
	 * The key for the cache item.
	 *
	 * @var    string
	 * @since  1.0
	 */
	private $key;

	/**
	 * The value of the cache item.
	 *
	 * @var    mixed
	 * @since  1.0
	 */
	private $value;

	/**
	 * Whether the cache item is value or not.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	private $hit;

	/**
	 * Class constructor.
	 *
	 * @param   string  $key  The key for the cache item.
	 *
	 * @since   1.0
	 */
	public function __construct($key)
	{
		$this->key = $key;
		$this->value = null;
		$this->hit = false;
	}

	/**
	 * Get the key associated with this CacheItem.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Obtain the value of this cache item.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Set the value of the item.
	 *
	 * If the value is set, we are assuming that there was a valid hit on the cache for the given key.
	 *
	 * @param   mixed  $value  The value for the cache item.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setValue($value)
	{
		$this->value = $value;
		$this->hit = true;
	}

	/**
	 * This boolean value tells us if our cache item is currently in the cache or not.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function isHit()
	{
		return $this->hit;
	}
}
