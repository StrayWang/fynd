<?php
require_once ('Fynd/UI/Component.php');
class Fynd_UI_Script extends Fynd_UI_Component
{
    /**
     * @var string
     */
    private $_src;
    /**
     * @var string
     */
    private $_key;
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }
    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->_src;
    }
    /**
     * @param string $_key
     */
    public function setKey($_key)
    {
        $this->_key = $_key;
    }
    /**
     * @param string $_src
     */
    public function setSrc($_src)
    {
        $this->_src = $_src;
    }

    /**
     * @see Fynd_UI_IComponent::initialize()
     *
     */
    public function initialize()
    {
        $this->getView()->addResource($this->_key,$this->_src);
    }
    /**
     * @see Fynd_UI_IComponent::render()
     *
     * @return string
     */
    public function render()
    {}
}
?>