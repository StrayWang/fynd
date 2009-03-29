<?php
require_once 'Fynd/Config/AbstractConfig.php';
class Fynd_Config_LogConfig extends Fynd_Config_AbstractConfig 
{
    public static $defaultConfigFile = "LogConfig.xml";
    
    const SYS_LOG_FILE = "system.log";
    
    protected $_defaultlogFile;
    /**
     * @see Fynd_Config_AbstractConfig::_initConfig()
     *
     */
    protected function _initConfig ()
    {
        if(!is_null($this->_rawXmlDoc) && false !== $this->_rawXmlDoc)
        {
            $this->_defaultlogFile = (string)$this->_rawXmlDoc->LogConfig->DefaultLogFile;
        }
        else
        {
            $this->_defaultlogFile = Fynd_Env::getLogPath() . self::SYS_LOG_FILE;
        }
    }
    
    public function getDefaultLogFile()
    {
        return $this->_defaultlogFile;
    }

}
?>