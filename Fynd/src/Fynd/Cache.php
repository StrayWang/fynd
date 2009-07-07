<?php
final class Fynd_Cache
{
    const APC          = 100;
    const EACCELERATOR = 101;
    const FILE_SYSTEM  = 102;
     
    /**
     * The cache storer.
     *
     * @var Fynd_Cache_IStorer
     */
    private static $_storer;
    
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
    public static function add($key, $var, $ttl = 60)
    {
        return self::$_storer->add($key, $var, $ttl);
    }
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
    public static function store($key, $var, $ttl = 60)
    {
        return self::$_storer->store($key, $var, $ttl);
    }
    /**
     * Fetch a stored variable from the cache.
     *
     * @param string $key
     * @return mixed In failure will return FALSE.please use "==="
     */
    public static function fetch($key)
    {
        return self::$_storer->fetch($key);
    }
    /**
     *  Removes a stored variable from the cache 
     *
     * @param string $key
     * @return bool Returns TRUE on success or FALSE on failure. 
     */
    public static function delete($key)
    {
        return self::$_storer->delete($key);     
    }
    /**
     * Clears the cache. 
     * @return bool Returns TRUE on success or FALSE on failure. 
     */
    public static function clear()
    {
        return self::$_storer->clear();
    }
    /**
     * Initialize the cache system.
     *
     */
    public static function init()
    {
        //TODO:Read cache configure to determine which cache storer will be created.
        self::$_storer = new Fynd_Cache_FileSystem();
        self::$_storer->init();
    }
    private function __construct()
    {}
}
?>
