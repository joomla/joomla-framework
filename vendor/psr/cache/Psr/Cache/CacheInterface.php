<?php

namespace Psr\Cache;

use Psr\Cache\CacheItemInterface;

interface CacheInterface
{

    /**
     * Here we pass in a cache key to be fetched from the cache.
     * A CacheItem object will be constructed and returned to us
     *
     * @param string $key The unique key of this item in the cache
     *
     * @return CacheItemInterface  The newly populated CacheItem class representing the stored data in the cache
     */
    public function get($key);

    /**
     * Persisting our data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string       $key The key of the item to store
     * @param mixed        $val The value of the item to store
     * @param null|integer $ttl Optional. The TTL value of this item. If no value is sent and the driver supports TTL
     *                          then the library may set a default value for it or let the driver take care of that.
     *
     * @return boolean
     */
    public function set($key, $val, $ttl = null);

    /**
     * Remove an item from the cache by its unique key
     *
     * @param string $key The unique cache key of the item to remove
     *
     * @return boolean    The result of the delete operation
     */
    public function remove($key);

    /**
     * Obtain multiple CacheItems by their unique keys
     *
     * @param array $keys A list of keys that can obtained in a single operation.
     *
     * @return array An array of CacheItem classes.
     *               The resulting array must use the CacheItem's key as the associative key for the array.
     */
    public function getMultiple($keys);

    /**
     * Persisting a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array        $items An array of key => value pairs for a multiple-set operation.
     * @param null|integer $ttl   Optional. The TTL value of this item. If no value is sent and the driver supports TTL
     *                            then the library may set a default value for it or let the driver take care of that.
     *
     * @return boolean The result of the multiple-set operation
     */
    public function setMultiple($items, $ttl = null);

    /**
     * Remove multiple cache items in a single operation
     *
     * @param array $keys The array of keys to be removed
     *
     * @return array An array of 'key' => result, elements. Each array row has the key being deleted
     *               and the result of that operation. The result will be a boolean of true or false
     *               representing if the cache item was removed or not
     */
    public function removeMultiple($keys);

    /**
     * This will wipe out the entire cache's keys
     *
     * @return boolean The result of the empty operation
     */
    public function clear();

}
