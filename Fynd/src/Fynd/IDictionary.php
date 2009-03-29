<?php
require_once ('Fynd/ICollection.php');
interface Fynd_IDictionary extends Fynd_ICollection
{
    /**
     * Add a new item to dictionay,if the item is exisited, update it.
     *
     * @param string | int $key
     * @param mixed $value
     */
    public function add($key,$value);
    /**
     * Remove the key and its value from dictionary.
     *
     * @param string | int $key
     */
    public function remove($key);
    /**
     * Remove all items.
     *
     */
    public function clear();
    /**
     * Determine the key is in the dictionary or not
     *
     * @param string | int $key
     */
    public function containsKey($key);
    /**
     * Convert to array
     * @return Array
     */
    public function toArray();
}
?>