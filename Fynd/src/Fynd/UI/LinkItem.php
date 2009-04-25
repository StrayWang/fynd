<?php
require_once ('Fynd/UI/OperationItem.php');
class Fynd_UI_LinkItem extends Fynd_UI_OperationItem
{
    /**
     * @var string
     */
    protected $_href;
    /**
     * @var string
     */
    protected $_target;
    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->_target;
    }
    /**
     * @param string $_target
     */
    public function setTarget($_target)
    {
        $this->_target = $_target;
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
        $replaced = preg_replace('/\{(.+?)\}/', "' + oRecord.getData('$1') + '", $_href);
        if(! is_null($replaced))
        {
            $this->_href = $replaced;
        }
        else
        {
            $this->_href = $_href;
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
        return "el.innerHTML += '<a href=\"{$this->_href}\" " . "class=\"{$this->getClass()}\" target=\"{$this->_target}\" title=\"{$this->getTitle()}\">{$this->_text}<\/a>';\n";
    }
}
?>