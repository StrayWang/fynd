<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class SequenceModel extends Fynd_Model
{
    private $_fieldName;
    private $_lastValue;
    
    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->_fieldName;
    }
    /**
     * @param string
     */
    public function setFieldName($_fieldName)
    {
        $this->_fieldName = $_fieldName;
    }
    /**
     * @return number
     */
    public function getLastValue()
    {
        return $this->_lastValue;
    }
    /**
     * @param number
     */
    public function setLastValue($_lastValue)
    {
        $this->_lastValue = $_lastValue;
    }
}
?>