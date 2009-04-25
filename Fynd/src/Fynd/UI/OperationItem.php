<?php
require_once ('Fynd/UI/Component.php');
abstract class Fynd_UI_OperationItem extends Fynd_UI_Component
{
    /**
     * @var string
     */
    protected $_text;
    public function __construct()
    {}
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
        $replaced = preg_replace('/\{(.+?)\}/', "' + oRecord.getData('$1') + '", $_text);
        if(! is_null($replaced))
        {
            $this->_text = $replaced;
        }
        else
        {
            $this->_text = $_text;
        }
    }
    /**
     * @see Fynd_UI_Component::setClass()
     *
     * @param string $cssClass
     */
    public function setClass($cssClass)
    {
        $replaced = preg_replace('/\{(.+?)\}/', "' + oRecord.getData('$1') + '", $cssClass);
        if(! is_null($replaced))
        {
            parent::setClass($replaced);
        }
        else
        {
            parent::setClass($cssClass);
        }
    }
    /**
     * @see Fynd_UI_Component::setTitle()
     *
     * @param string $tooltip
     */
    public function setTitle($tooltip)
    {
        $replaced = preg_replace('/\{(.+?)\}/', "' + oRecord.getData('$1') + '", $tooltip);
        if(! is_null($replaced))
        {
            parent::setTitle($replaced);
        }
        else
        {
            parent::setTitle($tooltip);
        }
    }
}
?>