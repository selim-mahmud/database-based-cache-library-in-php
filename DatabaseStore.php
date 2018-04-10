<?php

namespace Cache;

class DatabaseStore implements Store
{

    /**
     * @var \Cache $cache
     */
    protected $cache;

    /**
     * DatabaseStore constructor
     *
     * @param  Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {

        $cache = $this->cache->findByKeyName($key);

        // check if we have a cache record
        if (!isvalid($cache)) {
            return false;
        }

        // If this cache expiration time is past the current time, we will remove this
        if (time() >= (int) $cache['expiration']) {
            return false;
        }

        return unserialize(base64_decode($cache['value']));
    }

    /**
     * {@inheritdoc}
     */
    public function put($key, $value, $minutes = 1440)
    {
    	// check if we have a previous cache record having same key name
    	$cache = $this->cache->findByKeyName($key);
        if (isvalid($cache)) { //if record found with same key name, update the record with new value and expiration time
    		$this->cache->id = $cache['id'];
    		$this->cache->refresh();
    		$this->cache->value = base64_encode(serialize($value));
    		$this->cache->expiration = time() + (int)($minutes * 60);
        }else{ // if no record found, create new record
        	$expiration = time() + (int)($minutes * 60);
        	$this->cache->key_name = $key;
        	$this->cache->value = base64_encode(serialize($value));
        	$this->cache->expiration = $expiration;
        }

        return $this->cache->save();
    }


    /**
     * {@inheritdoc}
     */
    public function forget($id)
    {
        return $this->cache->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function flushExpired()
    {
        return $this->cache->deleteExpired();
    }


    /**
     * {@inheritdoc}
     */
    public function flushAll()
    {
        return $this->cache->deleteAll();
    }


}
