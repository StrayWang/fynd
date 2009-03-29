<?php
interface Fynd_Cache_IStorer
{
    /**
     * Add a new cache entity to the cahce storer.
     * If the key is already exist in the cache storer,it will return FALSE,
     * return TRUE,otherwise.
     *
     * @param string $key
     * @param mixed $var
     * @param int $ttl If no $ttl  is supplied (or if the $ttl is 0), 
     * the value will persist until it is removed from the cache manually, 
     * or otherwise fails to exist in the cache (clear, restart, etc.). 
     * 
     * @return bool
     */
    public function add($key,$var,$ttl);
    /**
     * Store a cache entity to the cache storer.
     * If the key is already exist in the cache storer,it will overwrite the exist one.
     * Store success return TRUE,FALSE otherwise. 
     *
     * @param string $key
     * @param mixed $var
     * @param int $ttl If no $ttl  is supplied (or if the $ttl is 0), 
     * the value will persist until it is removed from the cache manually, 
     * or otherwise fails to exist in the cache (clear, restart, etc.). 
     * 
     * @return bool
     */
    public function store($key,$var,$ttl);
    /**
     * Fetch a stored variable from the cache.
     *
     * @param string $key
     * @return mixed In failure will return FALSE.please use "==="
     */
    public function fetch($key);
    /**
     *  Removes a stored variable from the cache 
     *
     * @param string $key
     * @return bool Returns TRUE on success or FALSE on failure. 
     */
    public function delete($key);
    /**
     * Clears the cache. 
     * @return bool Returns TRUE on success or FALSE on failure. 
     */
    public function clear();
    /**
     * Initialize the cache storer.
     *
     */
    public function init();
}
?>