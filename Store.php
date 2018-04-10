<?php

namespace Cache;


interface Store
{
    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key);


    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  array|string $value
     * @param  float|int $minutes
     * @return bool
     */
    public function put($key, $value, $minutes);


    /**
     * Remove an item from the cache.
     *
     * @param  int $id
     * @return bool
     */
    public function forget($id);

    /**
     * Remove all expired items from the cache.
     *
     * @return bool
     */
    public function flushExpired();

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flushAll();

}
