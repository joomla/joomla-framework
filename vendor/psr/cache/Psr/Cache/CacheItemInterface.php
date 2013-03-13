<?php

namespace Psr\Cache;

interface CacheItemInterface
{

    /**
     * Get the key associated with this CacheItem
     *
     * @return string
     */
    public function getKey();

    /**
     * Obtain the value of this cache item
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set the value of the item
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * This boolean value tells us if our cache item is currently in the cache or not
     *
     * @return boolean
     */
    public function isHit();

}
