<?php
class Fynd_Log
{
    /**
     * log writer,write to stream
     *
     * @var Fynd_Log_IWriter
     */
    protected $_writer = null;
    /**
     * log meesage simple format
     *
     * @var string
     */
    protected $_format = "{msg_type}\t{time}\t{logger_name}\t{msg}\n";
    /**
     * the logger's name
     *
     * @var string
     */
    protected $_name;
    /**
     * collection of loggers
     *
     * @var array
     */
    protected static $_loggers;
    /**
     * log level error,which message describe error message
     *
     */
    const LOG_ERROR = 0;
    /**
     * log level warnning,which message describe warnning message
     *
     */
    const LOG_WARN = 1;
    /**
     * log level infomation,which message just contain debug infomation or other.
     *
     */
    const LOG_INFO = 2;
    /**
     * log the error message
     *
     * @param string $msg
     */
    public function logError($msg)
    {
        $this->_writer->write($this->_createLogMessage($msg, Fynd_Log::LOG_ERROR));
    }
    /**
     * log the warnning message
     *
     * @param string $msg
     */
    public function logWarn($msg)
    {
        $this->_writer->write($this->_createLogMessage($msg, Fynd_Log::LOG_WARN));
    }
    /**
     * log the infomation message
     *
     * @param string $msg
     */
    public function logInfo($msg)
    {
        $this->_writer->write($this->_createLogMessage($msg, Fynd_Log::LOG_INFO));
    }
    /**
     * create formated log message
     *
     * @param string $msg
     * @param int $level
     * @return string
     */
    protected function _createLogMessage($msg, $level)
    {
        $message = $this->_format;
        switch($level)
        {
            case Fynd_Log::LOG_INFO:
                $message = str_replace('{msg_type}', 'INFO', $message);
                break;
            case Fynd_Log::LOG_WARN:
                $message = str_replace('{msg_type}', 'WARN', $message);
                break;
            case Fynd_Log::LOG_ERROR:
                $message = str_replace('{msg_type}', 'ERROR', $message);
                break;
            default:
                $message = str_replace('{msg_type}', 'INFO', $message);
                break;
        }
        $message = str_replace('{time}', date('Y-m-d H:m:s'), $message);
        $message = str_replace('{logger_name}', $this->_name, $message);
        if(! is_scalar($msg))
        {
            $msg = var_export($msg, true);
        }
//        else if(is_string($msg))
//        {
//            $msg = utf8_encode($msg);
//        }
        $message = str_replace('{msg}', $msg, $message);
        return $message;
    }
    /**
     * constructor of Fynd_Log
     *
     * @param string $name
     * @param Fynd_Log_IWriter $writer
     */
    public function __construct($name, Fynd_Log_IWriter $writer)
    {
        if(empty($name))
        {
            Fynd_Object::throwException("Fynd_Log_Exception", '$name can not be null or empty');
        }
        if($name instanceof Fynd_Type)
        {
            $this->_name = $name->getClassName();
        }
        else
        {
            $this->_name = $name;
        }
        $this->_writer = $writer;
    }
    /**
     * do some work like close writer
     *
     */
    public function __destruct()
    {
        if(! is_null($this->_writer))
        {
            $this->_writer->close();
        }
    }
    /**
     * log message,but you may want to use the shortcut method for each log level
     *
     * @param string $msg
     * @param int $level
     */
    public function log($msg, $level)
    {
        switch($level)
        {
            case Fynd_Log::LOG_INFO:
                $this->logInfo($msg);
                break;
            case Fynd_Log::LOG_WARN:
                $this->logWarn($msg);
                break;
            case Fynd_Log::LOG_ERROR:
                $this->logError($msg);
                break;
            default:
                $this->logInfo($msg);
                break;
        }
    }
}
?>
