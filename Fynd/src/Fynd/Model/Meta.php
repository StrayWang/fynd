<?php
require_once 'Fynd/Object.php';
require_once 'Fynd/Model/State.php';
final class Fynd_Model_Meta extends Fynd_Object
{
    private $_tableName;
    private $_primaryKey;
    private $_primaryProperty;
    private $_state;
    private $_alias;
    /**
     * @return string
     */
    public function getPrimaryProperty()
    {
        return $this->_primaryProperty;
    }
    /**
     * @param string $_primaryProperty
     */
    public function setPrimaryProperty($_primaryProperty)
    {
        $this->_primaryProperty = $_primaryProperty;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }
    /**
     * @return int
     */
    public function getState()
    {
        return $this->_state;
    }
    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }
    /**
     * @param string $_primaryProperty
     */
    public function setPrimaryKey($_primaryKey)
    {
        $this->_primaryKey = $_primaryKey;
    }
    /**
     * @param int $_state
     */
    public function setState($state)
    {
        if ($state != Fynd_Model_State::ADDED     && 
            $state != Fynd_Model_State::DELETED   &&
            $state != Fynd_Model_State::MODIFIED  && 
            $state != Fynd_Model_State::NONE)
        {
            Fynd_Object::throwException("Fynd_Model_Exception",'$status is not validated');
        }
        $this->_state = $state;
    }
    /**
     * @param string $_tableName
     */
    public function setTableName($_tableName)
    {
        $this->_tableName = $_tableName;
    }
    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }
    /**
     * @param string $_alias
     */
    public function setAlias($_alias)
    {
        $this->_alias = $_alias;
    }


}
?>