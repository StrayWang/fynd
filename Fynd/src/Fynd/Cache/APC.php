<?php
require_once ('Fynd/Cache/IStorer.php');
require_once ('Fynd/Object.php');
class Fynd_Cache_APC extends Fynd_Object implements Fynd_Cache_IStorer
{
    /**
     * 
     * @param string $key 
     * @param mixed $var 
     * @param int $ttl 
     * @return bool 
     * @see Fynd_Cache_IStorer::add()
     */
    public function add($key, $var, $ttl)
    {
        return apc_add($key, $var, $ttl);
    }
    /**
     * 
     * @return bool Returns TRUE on success or FALSE on failure. 
     * @see Fynd_Cache_IStorer::clear()
     */
    public function clear()
    {
        return apc_clear_cache("user");
    }
    /**
     * 
     * @param string $key 
     * @return bool Returns TRUE on success or FALSE on failure. 
     * @see Fynd_Cache_IStorer::delete()
     */
    public function delete($key)
    {
        return apc_delete($key);
    }
    /**
     * 
     * @param string $key 
     * @return mixed In failure will return FALSE.please use "===" 
     * @see Fynd_Cache_IStorer::fetch()
     */
    public function fetch($key)
    {
        return apc_fetch($key);
    }
    /**
     * 
     * @see Fynd_Cache_IStorer::init()
     */
    public function init()
    {}
    /**
     * 
     * @param string $key 
     * @param mixed $var 
     * @param int $ttl 
     * @return bool 
     * @see Fynd_Cache_IStorer::store()
     */
    public function store($key, $var, $ttl)
    {
        return apc_store($key, $var, $ttl);
    }
}
?>