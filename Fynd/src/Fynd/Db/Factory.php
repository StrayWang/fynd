<?php
require_once 'Fynd/Dictionary.php';
final class Fynd_Db_Factory
{
    /**
     * @var Fynd_Dictionary
     */
    private static $_conns = null;
    private function __construct()
    {}
    /**
     * create database connection object from the configure,
     * now, support MySQL only
     *
     * @param Fynd_Config_DbConnectionConfig $connConfig
     * @return Fynd_Db_IConnection
     */
    public static function getConnection(Fynd_Config_DbConnectionConfig $connConfig = null)
    {
        if(is_null(self::$_conns))
        {
            self::$_conns = new Fynd_Dictionary();
        }
        if($connConfig == null)
        {
            $dbConfig = Fynd_Config_Manager::getConfig(
                    Fynd_Config_Type::DB_CONFIG, 
                    Fynd_Env::getConfigPath() . 'DbConfig.xml');
                    
            $connConfig = $dbConfig->getDefaultConnectionConfig();
        }
        $connId = self::_generateConnId($connConfig);
        if(self::$_conns->containsKey($connId))
        {
            return self::$_conns[$connId];
        }
        $conn = null;
        if($connConfig->getDbType() == Fynd_Db_Type::MYSQL)
        {
            $conn = new Fynd_Db_MySQLConnection();
            $conn->setConfig($connConfig); 
        }
        else 
        {
            Fynd_Object::throwException("Fynd_Db_Exception","Sorry,Fynd_Db_Factory support MySQL only.");    
        }
        self::$_conns->add($connId,$conn);
        return $conn;
    }
    
    private static function _generateConnId(Fynd_Config_DbConnectionConfig $connConfig)
    {
        $idStr = $connConfig->getDatabase() . 
                 $connConfig->getDbType()   .
                 $connConfig->getPassword() .
                 $connConfig->getPort()     .
                 $connConfig->getServer()   .
                 $connConfig->getUser();
        return md5($idStr);                 
    }
}
?>