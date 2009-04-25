<?php
require_once ('Fynd/UI/Component.php');
class Fynd_UI_Picker extends Fynd_UI_Component
{
    private $_href;
    private $_tableId;
    private $_tableIdKey;
    private $_tableLabelKey;
    private $_multiSelection;
    private $_callback;
    private $_text;
    /**
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }
    /**
     * @param string $_text
     */
    public function setText($_text)
    {
        $this->_text = $_text;
    }

    /**
     * @return bool
     */
    public function getMultiSelection()
    {
        return $this->_multiSelection;
    }
    /**
     * @param bool $_multiSelection
     */
    public function setMultiSelection($_multiSelection)
    {
        if($_multiSelection)
        {
            $this->_multiSelection = true;
        }
        else
        {
            $this->_multiSelection = false;
        }
    }
    /**
     * @return string
     */
    public function getTableIdKey()
    {
        return $this->_tableIdKey;
    }
    /**
     * @param string $_tableIdKey
     */
    public function setTableIdKey($_tableIdKey)
    {
        $this->_tableIdKey = $_tableIdKey;
    }
    /**
     * @return string
     */
    public function getTableLabelKey()
    {
        return $this->_tableLabelKey;
    }
    /**
     * @param string $_tableLabelKey
     */
    public function setTableLabelKey($_tableLabelKey)
    {
        $this->_tableLabelKey = $_tableLabelKey;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->_callback;
    }
    /**
     * @param string $_callback
     */
    public function setCallback($_callback)
    {
        $this->_callback = $_callback;
    }
    /**
     * @return string
     */
    public function getHref()
    {
        return $this->_href;
    }
    /**
     * @param string $_href
     */
    public function setHref($_href)
    {
        $this->_href = $_href;
    }
    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->_tableId;
    }
    /**
     * @param string $_tableId
     */
    public function setTableId($_tableId)
    {
        $this->_tableId = $_tableId;
    }
    /**
     * @see Fynd_UI_IComponent::initialize()
     *
     */
    public function initialize()
    {}
    /**
     * @see Fynd_UI_IComponent::render()
     *
     * @return string
     */
    public function render()
    {
        $tpl = file_get_contents(dirname(__FILE__) . '/Picker.html');
        
        $render = str_replace('{:Id}',$this->getID(),$tpl);
        $render = str_replace('{:TableId}',$this->_tableId,$render);
        $render = str_replace('{:MultiSelection}',(($this->_multiSelection == true) ? 'true' : 'false'),$render);
        $render = str_replace('{:TableIdKey}',$this->_tableIdKey,$render);
        $render = str_replace('{:TableLabelKey}',$this->_tableLabelKey,$render);
        $render = str_replace('{:Href}',$this->_href,$render);
        $render = str_replace('{:Class}',$this->getClass(),$render);
        $render = str_replace('{:Style}',$this->getStyle(),$render);
        $render = str_replace('{:Title}',$this->getTitle(),$render);
        $render = str_replace('{:Text}',$this->_text,$render);
        
        return $render;
    }


}
?>