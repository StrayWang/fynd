<?php
require_once ('Fynd/ICollection.php');
interface Fynd_IList extends Fynd_ICollection
{
    /**
     * Add a new item to list,if the item is exisited, update it.
     *
     * @param mixed $item
     */
    public function add($item);
    /**
     * Remove the item from list.
     *
     * @param mixed $item
     */
    public function remove($item);
    /**
     * Determine the item is in the list or not.
     *
     * @param mixed $item
     */
    public function contains($item);
    /**
     * Remove all items.
     *
     */
    public function clear();
    /**
     * Put the list items into a array
     * @return Array 
     *
     */
    public function toArray();
}
?>