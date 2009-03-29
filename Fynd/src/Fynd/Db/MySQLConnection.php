<?php
require_once ('Fynd/Db/IConnection.php');
require_once ('Fynd/Object.php');
class Fynd_Db_MySQLConnection extends Fynd_Object implements Fynd_Db_IConnection
{
    /**
     * @var PDO
     */
    protected $_pdo;
    /**
     * @var Fynd_Config_DbConnectionConfig
     */
    protected $_config;
    
    protected $_isBeginTrans = false;
    /**
     * @var Fynd_Dictionary
     */
    private $_stmtCache;
    
    public function __construct()
    {
        $this->_stmtCache = new Fynd_Dictionary();
    }
    /**
     * 
     * @return boolean 
     * @see Fynd_Db_IConnection::beginTrans()
     */
    public function beginTrans()
    {
        if($this->_isBeginTrans == false)
        {
            $this->_checkConnectionOpen();
            $this->_pdo->beginTransaction();
            $this->_isBeginTrans = true;
        }
    }
    /**
     * 
     * @return void 
     * @see Fynd_Db_IConnection::close()
     */
    public function close()
    {
        $this->_pdo = null;
    }
    /**
     * 
     * @return void 
     * @see Fynd_Db_IConnection::commit()
     */
    public function commit()
    {
        $this->_checkConnectionOpen();
        if($this->_isBeginTrans)
        {
            try
            {
                $this->_pdo->commit();
                $this->_isBeginTrans = false;
            }
            catch(Exception $e)
            {
                Fynd_Object::throwException("Fynd_Db_Exception", 
                		"Exception thrown where transaction committing:" . $e->getMessage(), 
                        $e->getCode());
            }
        }
    }
    /**
     * 
     * @param string $cmdText 
     * @return Fynd_Db_ICommand 
     * @see Fynd_Db_IConnection::createCommand()
     */
    public function createCommand($cmdText = "")
    {
        $cmd = new Fynd_Db_MySQLCommand($cmdText);
        $cmd->setConnection($this);
        return $cmd;
    }
    /**
     * 
     * @param string $sql 
     * @param Array $params 
     * @return Array 
     * @see Fynd_Db_IConnection::excute()
     */
    public function excute($sql, Array $params)
    {
        $this->_checkConnectionOpen();
        $stmt = null;
        $queryId = $this->_createQueryId($sql,$params);
        if($this->_stmtCache->containsKey($queryId))
        {
            $stmt = $this->_stmtCache[$queryId];
        }
        else 
        {
            $stmt = $this->_pdo->prepare($sql);
            if(! is_null($params))
            {
                foreach($params as $p)
                {
                    $pdoParamType = null;
                    if(Fynd_Db_DataType::NUMBER === $p->dataType)
                    {
                        $pdoParamType = PDO::PARAM_INT;
                    }
                    else if(Fynd_Db_DataType::STRING === $p->dataType)
                    {
                        $pdoParamType = PDO::PARAM_STR;
                    }
                    else if(Fynd_Db_DataType::DATETIME === $p->dataType)
                    {
                        $pdoParamType = PDO::PARAM_STR;
                    }
                    
                    $pdoParamDirection = null;
                    if(Fynd_Db_Parameter::IN     == $p->direction ||
                       Fynd_Db_Parameter::OUT    == $p->direction ||
                       Fynd_Db_Parameter::IN_OUT == $p->direction)
                    {
                        $pdoParamDirection = PDO::PARAM_INPUT_OUTPUT;
                    }
                    
                    if(is_null($pdoParamType))
                    {
                        $stmt->bindParam($p->name, $p->value);
                    }
                    else if(is_null($pdoParamDirection))
                    {
                        $stmt->bindParam($p->name, $p->value, $pdoParamType);
                    }
                    else 
                    {
                        $stmt->bindParam($p->name, $p->value, $pdoParamType | $pdoParamDirection);
                    }
                }
            }
            $this->_stmtCache->add($queryId,$stmt);
        }
        if(! $stmt->Execute())
        {
            Fynd_Object::throwException("Fynd_Db_Exception", $stmt->errorInfo(), $stmt->errorCode());
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    /**
     * 
     * @return boolean 
     * @see Fynd_Db_IConnection::open()
     */
    public function open()
    {
        $open = false;
        if(is_null($this->_pdo))
        {
            try
            {
                $dsn = 'mysql'   . 
                	   ':host='  . $this->_config->getServer()  . 
                	   ';port='  . $this->_config->getPort()    . 
                	   ';dbname='. $this->_config->getDatabase();
                
                $this->_pdo = new PDO($dsn, $this->_config->getUser(), $this->_config->getPassword());
                
                $this->_pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
                $this->_pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                
                $open = true;
            }
            catch(Exception $e)
            {
                Fynd_Object::throwException("Fynd_Db_Exception", $e->getMessage(),$e->getCode());
            }
        }
        return $open;
    }
    /**
     * 
     * @return void 
     * @see Fynd_Db_IConnection::rollback()
     */
    public function rollback()
    {
        $this->_checkConnectionOpen();
        if($this->_isBeginTrans)
        {
            try
            {
                $this->_pdo->rollBack();
                $this->_isBeginTrans = false;
            }
            catch(Exception $e)
            {
                Fynd_Object::throwException("Fynd_Db_Exception", 
                		"Exception thrown where transaction rollbacking:" . $e->getMessage(), 
                        $e->getCode());
            }
        }
    }
    /**
     * @see Fynd_Db_IConnection::getConfig()
     *
     * @return Fynd_Config_DbConnectionConfig
     */
    public function getConfig()
    {
        return $this->_config;
    }
    /**
     * @see Fynd_Db_IConnection::setConfig()
     *
     * @param Fynd_Config_DbConnectionConfig $connConfig
     */
    public function setConfig(Fynd_Config_DbConnectionConfig $connConfig)
    {
        $this->_config = $connConfig;
    }

    
    private function _checkConnectionOpen()
    {
        if(is_null($this->_pdo))
        {
            Fynd_Object::throwException("Fynd_Db_Exception", "The connection has not been open yet.");
        }
    }
    
    private function _createQueryId($sql,Array $params)
    {
        $paramStr = "";
        foreach($params as $param)
        {
            $paramStr .= $param->getHashCode();
        }
        $idStr = $sql . $paramStr;
        $id = md5($idStr);
        
        return $id;
    }
}
?>