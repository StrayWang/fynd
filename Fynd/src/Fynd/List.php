<?php
require_once ('Fynd/IList.php');
require_once ('Fynd/Collection.php');
class Fynd_List extends Fynd_Collection implements Fynd_IList
{
    /**
     * @see Fynd_IList::add()
     *
     * @param mixed $item
     */
    public function add($item)
    {
        array_push(&$this->_items,$item);
    }
    /**
     * @see Fynd_IList::clear()
     *
     */
    public function clear()
    {
        $this->_items = array();
    }
    /**
     * @see Fynd_IList::contains()
     *
     * @param mixed $item
     */
    public function contains($item)
    {
        return in_array($item,$this->_items,true);
    }
    /**
     * @see Fynd_IList::remove()
     *
     * @param mixed $item
     */
    public function remove($item)
    {
        $count = 0;
        $this->_removeItem($item,$count);
        if($count > 0)
        {
            //reindex the items
            $this->_items = array_values($this->_items);
        }
    }
    
    private function _removeItem($item,&$count)
    {
        $key = array_search($item,$this->_items,true);
        if(false === $key)
        {
            return;
        }
        else
        {
            unset($this->_items[$key]);
            $count ++;
            $this->_removeItem($item,$count);
        }
    }
    /**
     * @see Fynd_IList::toArray()
     *
     * @return Array
     */
    public function toArray()
    {
        return array_values($this->_items);
    }
}
?>