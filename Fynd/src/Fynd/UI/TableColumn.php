<?php
require_once ('Fynd/Object.php');
class Fynd_UI_TableColumn extends Fynd_Object
{
    /**
     * @var string
     */
    protected $_key;
    /**
     * @var string
     */    
    protected $_label;
    /**
     * @var int
     */    
    protected $_width;
    /**
     * @var bool
     */    
    protected $_resizeable;
    /**
     * @var bool
     */    
    protected $_sortable;
    /**
     * var Fynd_IList
     */
    protected $_operationItems;
    
    public function __construct($key = '', $label = '', $width = 100, $resizeable = false, $sortable = false)
    {
        $this->_key        = $key;
        $this->_label      = $label;
        $this->_width      = $width;
        $this->_sortable   = $sortable;
        $this->_resizeable = $resizeable;
        $this->_operationItems = new Fynd_List();
    }
    /**
     * @return Fynd_IList
     */
    public function getOperationItems()
    {
        return $this->_operationItems;
    }
    /**
     * @param Fynd_IList $_operationItems
     */
    public function setOperationItems(Fynd_IList $_operationItems)
    {
        $this->_operationItems = $_operationItems;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }
    /**
     * @param string $_key
     */
    public function setKey($_key)
    {
        $this->_key = $_key;
    }
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }
    /**
     * @param string $_label
     */
    public function setLabel($_label)
    {
        $this->_label = $_label;
    }
    /**
     * @return bool
     */
    public function getResizeable()
    {
        return $this->_resizeable;
    }
    /**
     * @param bool $_resizeable
     */
    public function setResizeable($_resizeable)
    {
        $this->_resizeable = (bool)$_resizeable;
    }
    /**
     * @return bool
     */
    public function getSortable()
    {
        return $this->_sortable;
    }
    /**
     * @param bool $_sortable
     */
    public function setSortable($_sortable)
    {
        $this->_sortable = (bool)$_sortable;
    }
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }
    /**
     * @param int $_width
     */
    public function setWidth($_width)
    {
        $this->_width = $_width;
    }

}
?>