<?php
require_once ('Fynd/Object.php');

/**
 * This class describe the relationship between models.
 * @author fishtrees
 * @version 20090308
 *
 */
final class Fynd_Model_Relationship extends Fynd_Object
{
    private $_child;
    private $_parent;
    private $_parentId;
    /**
     * @return Fynd_Model
     */
    public function getChild()
    {
        return $this->_child;
    }
    /**
     * @return Fynd_Model
     */
    public function getParent()
    {
        return $this->_parent;
    }
    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->_parentId;
    }
    public function __constract($child,$parent,$parentId)
    {
        $this->_child    = $child;
        $this->_parent   = $parent;
        $this->_parentId = $parentId;
    }
}
?>