<?php

namespace Cache;


class CacheManager
{

    /**
     * @var Store $store
     */
    protected $store;

    /**
     * CacheManager constructor
     *
     * @param  Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }


    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->store->get($key);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  array|string $value
     * @param  float|int $minutes
     * @return bool
     */
    public function set($key, $value, $minutes = 1440)
    {
        return $this->store->put($key, $value, $minutes);
    }


    /**
     * Remove all expired items from the cache.
     *
     * @return bool
     */
    public function flushExpired()
    {
        return $this->store->flushExpired();
    }


    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flushAll()
    {
        return $this->store->flushAll();
    }


}
