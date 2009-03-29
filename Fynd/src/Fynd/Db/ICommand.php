<?php
interface Fynd_Db_ICommand
{
    /**
     * @return   Fynd_Db_IConnection
     */
    public function getConnection();
    
    /**
     * @param    Fynd_Db_IConnection $conn    
     * @return   void
     */
    public function setConnection(Fynd_Db_IConnection $conn);
    
    /**
     * @return   string
     */
    public function getCommandText();
    
    /**
     * @param    string $text    
     * @return   void
     */
    public function setCommandText($text);
    
    /**
     * @param    Fynd_Db_Parameter $param    
     * @return   void
     */
    public function addParameter(Fynd_Db_Parameter $param);
    
    /**
     * @return   array
     */
    public function execute();
    
    /**
     * @return   scalar
     */
    public function executeScalar();
    
    /**
     * @return   int
     */
    public function excuteNonQuery();
}
?>