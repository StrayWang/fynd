<?php
require_once ('Fynd/Cache/IStorer.php');
require_once ('Fynd/Object.php');
class Fynd_Cache_eAccelerator extends Fynd_Object implements Fynd_Cache_IStorer
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        //TODO - Insert your code here
    }
    /**
     * 
     * @param string $key 
     * @param mixed $var 
     * @param int $ttl 
     * @return bool 
     * @see Fynd_Cache_IStorer::add()
     */
    public function add($key, $var, $ttl)
    {//TODO - Insert your code here
}
    /**
     * 
     * @return bool Returns TRUE on success or FALSE on failure. 
     * @see Fynd_Cache_IStorer::clear()
     */
    public function clear()
    {//TODO - Insert your code here
}
    /**
     * 
     * @param string $key 
     * @return bool Returns TRUE on success or FALSE on failure. 
     * @see Fynd_Cache_IStorer::delete()
     */
    public function delete($key)
    {//TODO - Insert your code here
}
    /**
     * 
     * @param string $key 
     * @return mixed In failure will return FALSE.please use "===" 
     * @see Fynd_Cache_IStorer::fetch()
     */
    public function fetch($key)
    {//TODO - Insert your code here
}
    /**
     * 
     * @see Fynd_Cache_IStorer::init()
     */
    public function init()
    {//TODO - Insert your code here
}
    /**
     * 
     * @param string $key 
     * @param mixed $var 
     * @param int $ttl 
     * @return bool 
     * @see Fynd_Cache_IStorer::store()
     */
    public function store($key, $var, $ttl)
    {//TODO - Insert your code here
}
}
?>