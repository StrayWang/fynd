<?php
require_once ('Fynd/UI/Container.php');
class Fynd_UI_Form extends Fynd_UI_Container
{
    /**
     * @var      string
     */
    protected $_action;
    /**
     * @var      string
     */
    protected $_formName;
    /**
     * @var      string
     */
    protected $_method;
    /**
     * @var      Fynd_IList
     */
    protected $_fields;
    /**
     * @var      Fynd_IList
     */
    protected $_buttons;

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }
    /**
     * @param string $_action
     */
    public function setAction($_action)
    {
        $this->_action = $_action;
    }
    /**
     * @return Fynd_IList
     */
    public function getButtons()
    {
        return $this->_buttons;
    }
    /**
     * @param Fynd_IList $_buttons
     */
    public function setButtons($_buttons)
    {
        $this->_buttons = $_buttons;
    }
    /**
     * @return Fynd_IList
     */
    public function getFields()
    {
        return $this->_fields;
    }
    /**
     * @param Fynd_IList $_fields
     */
    public function setFields($_fields)
    {
        $this->_fields = $_fields;
    }
    /**
     * @return string
     */
    public function getFormName()
    {
        return $this->FormName;
    }
    /**
     * @param string $_formName
     */
    public function setFormName($_formName)
    {
        $this->_formName = $_formName;
    }
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }
    /**
     * @param string $_method
     */
    public function setMethod($_method)
    {
        $this->_method = $_method;
    }
    /**
     * @see Fynd_UI_IComponent::render()
     *
     * @return string
     */
    public function render()
    {
        $html = "<form action=\"{$this->_action}\" id=\"{$this->getID()}\">" . $this->_cmpFactory->parse($this->_preParsedHtml, $this) . "</form>";
        $tpl = file_get_contents(dirname(__FILE__) . '/Form.html');
        
        $render = str_replace('{:html}',$html,$tpl);
        $render = str_replace('{:formId}',$this->getID(),$render);
        
        $formFieldIds = "[";
        $cmps = $this->getComponents(); 
        foreach($cmps as $cmp)
        {
            if(cmp)
            {
                $formFieldIds .= "'{$cmp->getID()}',"; 
            }
        }
        if(Fynd_StringUtil::endWith($formFieldIds,","))
        {
            $formFieldIds = Fynd_StringUtil::removeEnd($formFieldIds);
        }
        $formFieldIds .= "]";
        
        $render = str_replace('{:formFieldIds}',$formFieldIds,$render);
        
        return $render;
    }
    /**
     * @see Fynd_UI_IContainer::getHtmlView()
     *
     * @return Fynd_View_Html
     */
    public function getHtmlView()
    {
        return $this->_view;
    }
}
?>