<?php
interface Fynd_Db_IConnection
{
    /**
     * @return   boolean
     */
    public function open();
    
    /**
     * @return   void
     */
    public function close();
    
    /**
     * @return   boolean
     */
    public function beginTrans();
    
    /**
     * @return   void
     */
    public function commit();
    
    /**
     * @return   void
     */
    public function rollback();
    
    /**
     * @param    string $sql    
     * @param    array $params    
     * @return   array
     */
    public function excute($sql, Array $params);
    
    /**
     * @param    string $cmdText    
     * @return   Fynd_Db_ICommand
     */
    public function createCommand($cmdText = "");
    /**
     * Set the database connection configure object
     *
     * @param Fynd_Config_DbConnectionConfig $connConfig
     */
    public function setConfig(Fynd_Config_DbConnectionConfig $connConfig);
    /**
     * Get the database connection configure object
     *
     * @return Fynd_Config_DbConnectionConfig
     */
    public function getConfig();
}
?>