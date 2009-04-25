<?php
require_once ('Fynd/UI/FormField.php');
class Fynd_UI_Input extends Fynd_UI_FormField
{
    private $_type = 'text';
    private $_disabled = false;
    private $_maxlength = null;
    private $_readonly = false;
    private $_size = null;
    
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_IMAGE = 'image';
    const TYPE_BUTTON = 'button';
    const TYPE_SUBMIT = 'submit';
    const TYPE_RESET = 'reset';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_FILE = 'file';
    
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }
    /**
     * @param string $_type
     */
    public function setType($_type)
    {
        $this->_type = $_type;
    }

    /**
     * @return bool
     */
    public function getDisabled()
    {
        return $this->_disabled;
    }
    /**
     * @param bool $_disabled
     */
    public function setDisabled($_disabled)
    {
        $this->_disabled = $_disabled;
    }
    /**
     * @return int
     */
    public function getMaxlength()
    {
        return $this->_maxlength;
    }
    /**
     * @param int $_maxlength
     */
    public function setMaxlength($_maxlength)
    {
        $this->_maxlength = $_maxlength;
    }
    /**
     * @return bool
     */
    public function getReadonly()
    {
        return $this->_readonly;
    }
    /**
     * @param bool $_readonly
     */
    public function setReadonly($_readonly)
    {
        $this->_readonly = $_readonly;
    }
    /**
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }
    /**
     * @param int $_size
     */
    public function setSize($_size)
    {
        $this->_size = $_size;
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
        $render = $this->_renderGenericInput();
        switch ($this->_type)
        {
            case self::TYPE_TEXT:
            case self::TYPE_PASSWORD:
                $render = $this->_renderTextInput($render);
                break;
            case self::TYPE_FILE:
                $render = $this->_renderFileInput($render);
                break;
        }
        return $render;
    }
    
    private function _renderGenericInput()
    {
        $html  = "<input type=\"{$this->_type}\" ";
        $html .= "id=\"{$this->getID()}\" ";
        $html .= "class=\"{$this->getClass()}\" ";
        $html .= "title=\"{$this->getTitle()}\" ";
        $html .= "style=\"{$this->getStyle()}\" ";
        
        return $html;
    }
    private function _renderTextInput($html)
    {
        $html .= "name=\"{$this->getName()}\" ";
        $html .= "value=\"{$this->getValue()}\" ";
        
        if(! is_null($this->_size))
        {
            $html .= "size=\"{$this->_size}\" ";
        }
        if(! is_null($this->_maxlength))
        {
            $html .= "maxlength=\"{$this->_maxlength}\" ";
        }
        if(true == $this->_disabled)
        {
            $html .= "disabled=\"disabled\" ";
        }
        if(true == $this->_readonly)
        {
            $html .= "readonly=\"readonly\" ";
        }
        $html .= " />";
        
        return $html;
    }
    public function _renderFileInput($html)
    {
        $html .= "name=\"{$this->getName()}\" />";
        return $html;
    }
}
?>