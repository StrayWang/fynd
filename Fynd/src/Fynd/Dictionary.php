<?php
require_once ('Fynd/Collection.php');
require_once ('Fynd/IDictionary.php');
class Fynd_Dictionary extends Fynd_Collection implements Fynd_IDictionary
{
    /**
     * @see Fynd_IDictionary::add()
     *
     * @param string | int $key
     * @param mixed $value
     */
    public function add($key, $value)
    {
        $this->_items[$key] = $value;
    }
    /**
     * @see Fynd_IDictionary::clear()
     *
     */
    public function clear()
    {
        $this->_items = array();
    }
    /**
     * @see Fynd_IDictionary::containsKey()
     *
     * @param string | int $key
     */
    public function containsKey($key)
    {
        return array_key_exists($key,$this->_items);
    }
    /**
     * @see Fynd_IDictionary::remove()
     *
     * @param string | int $key
     */
    public function remove($key)
    {
        $keys = array_keys($this->_items,$key,true);
        foreach ($keys as $key1)
        {
            unset($this->_items[$key1]);
        }
    }
    /**
     * @see Fynd_IDictionary::toArray()
     *
     * @return Array
     */
    public function toArray()
    {
        return $this->_items;
    }
}
?>