<?php
require_once 'Fynd/Model.php';
class DatabaseModel extends Fynd_Model
{
    protected $_host;
    protected $_user;
    protected $_password;
    protected $_databaseName;
    protected $_port;
    protected $_databaseType;
    protected $_tables = null;
    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->_databaseName;
    }
    /**
     * @param string $_databaseName
     */
    public function setDatabaseName($_databaseName)
    {
        $this->_databaseName = $_databaseName;
    }
    /**
     * @return string
     */
    public function getHost()
    {
        return $this->_host;
    }
    /**
     * @param string $_host
     */
    public function setHost($_host)
    {
        $this->_host = $_host;
    }
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }
    /**
     * @param string $_password
     */
    public function setPassword($_password)
    {
        $this->_password = $_password;
    }
    /**
     * @return int
     */
    public function getPort()
    {
        return $this->_port;
    }
    /**
     * @param int $_port
     */
    public function setPort($_port)
    {
        $this->_port = $_port;
    }
    /**
     * @return int
     */
    public function getDatabaseType()
    {
        return $this->_databaseType;
    }
    /**
     * @param int $dbType Use Fynd_Db_Type to describe.
     */
    public function setDatabaseType($dbType)
    {
        $this->_databaseType = $dbType;
    }
    /**
     * @return Fynd_List $_tables
     */
    public function getTables()
    {
        return $this->_tables;
    }
    /**
     * @param Fynd_List $_tables
     */
    public function setTables($_tables)
    {
        $this->_tables = $_tables;
    }
    /**
     * @return string
     */
    public function getUser()
    {
        return $this->_user;
    }
    /**
     * @param string $_user
     */
    public function setUser($_user)
    {
        $this->_user = $_user;
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