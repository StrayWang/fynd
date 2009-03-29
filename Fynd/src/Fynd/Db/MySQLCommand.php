<?php
require_once ('Fynd/Db/ICommand.php');
require_once ('Fynd/Object.php');
class Fynd_Db_MySQLCommand extends Fynd_Object implements Fynd_Db_ICommand
{
    /**
     * @var Fynd_List
     */
    private $_params;
    /**
     * @var Fynd_Db_MySQLConnection
     */
    private $_conn;
    private $_cmdText;
    public function __construct($cmdText = '')
    {
        $this->_params = new Fynd_List();
        $this->_cmdText = $cmdText;
    }
    /**
     * 
     * @param Fynd_Db_Parameter $param 
     * @return void 
     * @see Fynd_Db_ICommand::addParameter()
     */
    public function addParameter(Fynd_Db_Parameter $param)
    {
        $this->_params->add($param);
    }
    /**
     * 
     * @return int 
     * @see Fynd_Db_ICommand::excuteNonQuery()
     */
    public function excuteNonQuery()
    {
        $this->_conn->excute($this->_cmdText, $this->_params->ToArray());
    }
    /**
     * 
     * @return array 
     * @see Fynd_Db_ICommand::execute()
     */
    public function execute()
    {
        return $this->_conn->excute($this->_cmdText, $this->_params->ToArray());
    }
    /**
     * 
     * @return scalar 
     * @see Fynd_Db_ICommand::executeScalar()
     */
    public function executeScalar()
    {
        $res = $this->_conn->excute($this->_cmdText, $this->_params->ToArray());
        if(count($res) > 0)
        {
            return array_shift($res);
        }
        return null;
    }
    /**
     * 
     * @return string 
     * @see Fynd_Db_ICommand::getCommandText()
     */
    public function getCommandText()
    {
        return $this->_cmdText;
    }
    /**
     * 
     * @return Fynd_Db_MySQLConnection 
     * @see Fynd_Db_ICommand::getConnection()
     */
    public function getConnection()
    {
        return $this->_conn;
    }
    /**
     * 
     * @param string $text 
     * @return void 
     * @see Fynd_Db_ICommand::setCommandText()
     */
    public function setCommandText($text)
    {
        $this->_cmdText = $text;
    }
    /**
     * 
     * @param Fynd_Db_IConnection $conn 
     * @return void 
     * @see Fynd_Db_ICommand::setConnection()
     */
    public function setConnection(Fynd_Db_IConnection $conn)
    {
        $this->_conn = $conn;
    }
}
?>