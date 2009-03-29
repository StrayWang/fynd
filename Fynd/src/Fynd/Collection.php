<?php
require_once ('Fynd/ICollection.php');
require_once ('Fynd/Object.php');
abstract class Fynd_Collection extends Fynd_Object implements Fynd_ICollection
{
    /**
     * Items in the collection
     *
     * @var array
     */
    protected $_items = array();
    /**
     * @param array $items
     */
    public function __construct(Array $items = array())
    {
        foreach($items as $key => $value)
        {
            $this->_items[$key] = $value;
        }
    }
    /**
     * 用于实现Iterator接口
     *
     * @var boolean
     */
    private $_iterationPointerValid;
    /**
     * @see ArrayAccess::offsetExists()
     *
     * @param offset $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->_items[$offset]);
    }
    /**
     * @see ArrayAccess::offsetGet()
     *
     * @param offset $offset
     */
    public function offsetGet($offset)
    {
        return $this->_items[$offset];
    }
    /**
     * @see ArrayAccess::offsetSet()
     *
     * @param offset $offset
     * @param value $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_items[$offset] = $value;
    }
    /**
     * @see ArrayAccess::offsetUnset()
     *
     * @param offset $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }
    /**
     * @see Countable::count()
     *
     */
    public function count()
    {
        return count($this->_items);
    }
    /**
     * @see Iterator::current()
     *
     */
    public function current()
    {
        return current($this->_items);
    }
    /**
     * @see Iterator::key()
     *
     */
    public function key()
    {
        return key($this->_items);
    }
    /**
     * @see Iterator::next()
     *
     */
    public function next()
    {
        $fail = next($this->_items);
        if($fail === false)
        {
            $this->_iterationPointerValid = false;
        }
        else
        {
            $this->_iterationPointerValid = true;
        }
    }
    /**
     * @see Iterator::rewind()
     *
     */
    public function rewind()
    {
        reset($this->_items);
        $this->_iterationPointerValid = true;
    }
    /**
     * @see Iterator::valid()
     *
     */
    public function valid()
    {
        return $this->_iterationPointerValid;
    }
}
?>