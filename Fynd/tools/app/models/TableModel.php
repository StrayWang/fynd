<?php
require_once 'Fynd/Model.php';
class TableModel extends Fynd_Model
{
    /**
     * @var string
     */
    protected $_tableName;
    /**
     * @var Fynd_List
     */
    protected $_fields = null;
    /**
     * @var FieldModel
     */
    protected $_primaryField = null;
    /**
     * @return Fynd_List
     */
    public function getFields()
    {
        return $this->_fields;
    }
    /**
     * @param Fynd_List $_fields
     */
    public function setFields($_fields)
    {
        $this->_fields = $_fields;
    }
    /**
     * @return FieldModel
     */
    public function getPrimaryField()
    {
        return $this->_primaryField;
    }
    /**
     * @param FieldModel $_primaryField
     */
    public function setPrimaryField($_primaryField)
    {
        $this->_primaryField = $_primaryField;
    }
    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }
    /**
     * @param string $_tableName
     */
    public function setTableName($_tableName)
    {
        $this->_tableName = $_tableName;
    }
    /**
     * @see Serializable::serialize()
     *
     */
    public function serialize()
    {}
    /**
     * @see Serializable::unserialize()
     *
     * @param serialized $serialized
     */
    public function unserialize($serialized)
    {}
}
?>