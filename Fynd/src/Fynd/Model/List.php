<?php
require_once ('Fynd/List.php');
/**
 * This class hold multi-models,can be iterated,and provide some useful method 
 * to access each model in the collection.
 * @author fishtrees
 * @version 20090308
 *
 */
class Fynd_Model_List extends Fynd_List
{
    /**
     * @see Fynd_Collection::offsetSet()
     *
     * @param int $offset
     * @param Fynd_Model $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_items[$offset] = $value;
    }
    /**
     * @see Fynd_List::add()
     *
     * @param Fynd_Model $item
     */
    public function add($item)
    {
        array_push(&$this->_items, $item);
    }
}
?>