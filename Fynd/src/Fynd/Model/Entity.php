<?php
require_once 'Fynd/Object.php';
class Fynd_Model_Entity extends Fynd_Object
{
    /**
     * Identify the entity has been modified or not.
     *
     * @var int
     */
    private $_state;
    /**
     * The sequence object for the entity
     *
     * @var Fynd_Model_Sequence
     */
    private $_seq;
    /**
     * The model's property name.
     *
     * @var string
     */
    private $_property;
    /**
     * The data table's field name.
     *
     * @var string
     */
    private $_field;
    /**
     * @var int
     */
    private $_dataType;
    /**
     * The model which hold this entity.
     *
     * @var Fynd_Model
     */
    private $_model;
    /**
     * Gets the field's data type.
     * use constants of Fynd_Db_DataType to describe 
     * 
     * @return int
     */
    public function getDataType()
    {
        return $this->_dataType;
    }
    /**
     * @return string
     */
    public function getField()
    {
        return $this->_field;
    }
    /**
     * @return Fynd_Model
     */
    public function getModel()
    {
        return $this->_model;
    }
    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->_property;
    }
    /**
     * Sets the field's data type.
     * use constants of Fynd_Db_DataType to describe 
     * @param int $_dataType
     */
    public function setDataType($_dataType)
    {
        $this->_dataType = $_dataType;
    }
    /**
     * @param string $_field
     */
    public function setField($_field)
    {
        $this->_field = $_field;
    }
    /**
     * @param Fynd_Model $_model
     */
    public function setModel(Fynd_Model $_model)
    {
        $this->_model = $_model;
    }
    /**
     * @param string $_property
     */
    public function setProperty($_property)
    {
        $this->_property = $_property;
    }
    /**
     * @return Fynd_Model_Sequence
     */
    public function getSeq()
    {
        return $this->_seq;
    }
    /**
     * @param Fynd_Model_Sequence $_seq
     */
    public function setSeq($_seq)
    {
        $this->_seq = $_seq;
    }
    /**
     *
     * @return int
     */
    public function getState()
    {
        return $this->_state;
    }
    /**
     * Set the entity's modified state.Please use Fynd_Model_State
     *
     * @param int $state
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

}
?>