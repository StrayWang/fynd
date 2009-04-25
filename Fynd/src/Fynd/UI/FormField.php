<?php
require_once ('Fynd/UI/Component.php');
abstract class Fynd_UI_FormField extends Fynd_UI_Component
{
    /**
     * @var string
     */
    private $_name;
    /**
     * @var mixed
     */
    private $_value;
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    /**
     * @param string $_name
     */
    public function setName($_name)
    {
        $this->_name = $_name;
    }
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
    /**
     * @param mixed $_value
     */
    public function setValue($_value)
    {
        $this->_value = $_value;
    }

}
?>