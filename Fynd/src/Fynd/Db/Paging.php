<?php
require_once ('Fynd/Db/ISQLBuilder.php');
require_once ('Fynd/Object.php');
class Fynd_Db_Paging extends Fynd_Object implements Fynd_Db_IPaging
{
    private $_pageSize;
    private $_pageNo;
    private $_orginSQL;
    /**
     * @var Fynd_Type
     */
    private $_connType;
    /**
     * 
     */
    public function __construct($pageNo = 1, $pageSize = 100, Fynd_Type $connType = null)
    {
        $this->_pageNo = $pageNo;
        $this->_pageSize = $pageSize;
        $this->_connType = $connType;
    }
    /**
     * @see Fynd_Db_IPaging::getConnectionObjectType()
     *
     * @return Fynd_Type
     */
    public function getConnectionObjectType()
    {
        return $this->_connType;
    }
    /**
     * @see Fynd_Db_IPaging::getPageNo()
     *
     * @return int
     */
    public function getPageNo()
    {
        return $this->_pageNo;
    }
    /**
     * @see Fynd_Db_IPaging::getPageSize()
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }
    /**
     * @see Fynd_Db_IPaging::setConnectionObjectType()
     *
     * @param Fynd_Type $type
     */
    public function setConnectionObjectType($type)
    {
        $this->_connType = $type;
    }
    /**
     * @see Fynd_Db_IPaging::setPageNo()
     *
     * @param int $pageNo
     */
    public function setPageNo($pageNo)
    {
        if($pageNo < 1)
        {
            Fynd_Object::throwException("Fynd_Db_Exception", "The page NO. can not be less than 1.");
        }
        $this->_pageNo = $pageNo;
    }
    /**
     * @see Fynd_Db_IPaging::setPageSize()
     *
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        if($pageSize < 1)
        {
            Fynd_Object::throwException("Fynd_Db_Exception", "The page size can not be less than 1.");
        }
        $this->_pageSize = $pageSize;
    }
    /**
     * @see Fynd_Db_IPaging::getOrginSQL()
     *
     * @return string
     */
    public function getOrginSQL()
    {
        return $this->_orginSQL;
    }
    /**
     * @see Fynd_Db_IPaging::setOrginSQL()
     *
     * @param string $sql
     */
    public function setOrginSQL($sql)
    {
        $this->_orginSQL = $sql;
    }
    /**
     * 
     * @return string 
     * @see Fynd_Db_ISQLBuilder::createSQL()
     */
    public function createSQL()
    {
        $type = new Fynd_Type("Fynd_DB_MySQLConnection");
        if($this->_connType->equls($type))
        {
            return $this->_createMySQLPagingSQL();
        }
        return "";
    }
    private function _createMySQLPagingSQL()
    {
        $sql = $this->_orginSQL . " LIMIT ";
        $offset = $this->_pageNo * $this->_pageSize - $this->_pageSize;
        $length = $this->_pageSize;
        $sql .= $offset . "," . $length;
        return $sql;
    }
}
?>