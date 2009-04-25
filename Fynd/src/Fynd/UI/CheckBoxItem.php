<?php
require_once ('Fynd/UI/OperationItem.php');
class Fynd_UI_CheckBoxItem extends Fynd_UI_OperationItem
{
    /**
     * @var string
     */
    protected $_formFieldValue;
    /**
     * @var string
     */
    protected $_formFeildname = 'FyndTableCheckBox';
    /**
     * @return string
     */
    public function getFormFeildname()
    {
        return $this->_formFeildname;
    }
    /**
     * @param string $_formFeildname
     */
    public function setFormFeildname($_formFeildname)
    {
        $replaced = preg_replace('/\{(.+?)\}/', "' + oRecord.getData('$1') + '", $_formFeildname);
        if(! is_null($replaced))
        {
            $this->_formFeildname = $replaced;
        }
        else
        {
            $this->_formFeildname = $_formFeildname;
        }
    }
    /**
     * @return string
     */
    public function getFormFieldValue()
    {
        return $this->_formFieldValue;
    }
    /**
     * @param string $_formFieldValue
     */
    public function setFormFieldValue($_formFieldValue)
    {
        $replaced = preg_replace('/\{(.+?)\}/', "' + oRecord.getData('$1') + '", $_formFieldValue);
        if(! is_null($replaced))
        {
            $this->_formFieldValue = $replaced;
        }
        else
        {
            $this->_formFieldValue = $_formFieldValue;
        }
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
        $render = "el.innerHTML += '<span><input type=\"checkbox\" id=\"{$this->getID()}\" name=\"{$this->_formFeildname}\" value=\"{$this->_formFieldValue}\" class=\"{$this->getClass()}\" title=\"{$this->getTitle()}\" />";
        if(! empty($this->_text))
        {
            $render .= "<lable for=\"\">{$this->_text}</label>";
        }
        $render .= "</span>';\n";
        return $render;
    }
}
?>