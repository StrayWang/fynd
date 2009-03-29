<?php
require_once 'Fynd/Object.php';
require_once 'Fynd/Db/ICommand.php';
class Fynd_Db_Command extends Fynd_Object implements Fynd_Db_ICommand
{
    private $_params = array();
    /**
     * @var Fynd_Db_IConnection
     */
    private $_db;
    
    private $_cmdText;
    /**
     * @see Fynd_Db_ICommand::addParameter()
     *
     * @param Fynd_Db_Parameter $param
     */
    public function addParameter(Fynd_Db_Parameter $param)
    {
        if(!in_array($param,$this->_params))
        {
            array_push($this->_params,$param);
        }
    }
    /**
     * @see Fynd_Db_ICommand::execute()
     *
     */
    public function execute()
    {
        $this->_db->query($this->_cmdText);
    }
    /**
     * Get data base abstract layer object
     *
     * @return Fynd_Db_IConnection
     */
    public function getDbObject()
    {
        return $this->_db;
    }
    /**
     * Set data base abstract layer object
     *
     * @param Fynd_Db $db
     */
    public function setDbObject(Fynd_Db $db)
    {
        $this->_db = $db;
    }
    /**
     * @return string
     */
    public function getCommandText()
    {
        return $this->_cmdText;
    }
    /**
     * @see Fynd_Db_ICommand::setCommandText()
     *
     * @param string $text
     */
    public function setCommandText($text)
    {
        $this->_cmdText = $text;
    }

    
}
?>