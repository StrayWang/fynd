<?php
require_once 'Fynd/UI/Component.php';
class Fynd_UI_HeadLink extends Fynd_UI_Component
{
    /**
     * @var string
     */
    protected $_href;
    /**
     * @var string
     */
    protected $_key;
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
    public function getHref()
    {
        return $this->_href;
    }
    /**
     * @param string $_href
     */
    public function setHref($_href)
    {
        $this->_href = str_replace('~/', Fynd_Env::getAppPath(), $_href);
    }
    /**
     * @see Fynd_UI_IComponent::initialize()
     *
     */
    public function initialize()
    {
        $this->getView()->addResource($this->_key,$this->_href);
    }
    /**
     * @see Fynd_UI_IComponent::render()
     *
     * @return string
     */
    public function render()
    {
//        $render = "<link ";
//        if(Fynd_StringUtil::endWith($this->_href,'.css'))
//        {
//            $render .= "rel=\"stylesheet\" type=\"text/css\" ";
//        }
//        $render .= "href=\"{$this->_href}\">\n";
//        return $render;
    }
}
?>