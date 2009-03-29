<?php
require_once 'Fynd/Object.php';
require_once 'IWriter.php';
/**
 * Stream writer of log object,write the log message to stream
 *
 */
class Fynd_Log_StreamWriter extends Fynd_Object implements Fynd_Log_IWriter 
{
    protected $_stream = null;
    /**
     * create a new writer
     *
     * @param string|stream $streamOrFile
     */
    public function __construct($streamOrFile)
    {
        if(is_resource($streamOrFile))
        {
            $this->_stream = $streamOrFile;
        }
        else
        {
            $this->_stream = @fopen($streamOrFile,'a',false);
            if($this->_stream === false)
            {
                Fynd_Object::throwException("Fynd_Log_Exception",'Can not open log stream,$streamOrFile = '.$streamOrFile);
            }
        }
        
        if(get_resource_type($this->_stream) != 'stream')
        {
            Fynd_Object::throwException("Fynd_Log_Exception",'Resource is not a stream');
        }
    }
    /**
     * get stream of writer
     *
     * @return resource
     */
    public function getStream()
    {
        return $this->_stream;
    }
    /**
     * @see Fynd_Log_IWriter::close()
     *
     */
    public function close ()
    {
        if(is_resource($this->_stream))
        {
            fclose($this->_stream);
        }
    }
    /**
     * @see Fynd_Log_IWriter::write()
     *
     */
    public function write ($msg)
    {
        if(is_resource($this->_stream))
        {
            $byteCount = @fwrite($this->_stream,$msg);
            if($byteCount === false)
            {
                Fynd_Object::throwException("Fynd_Log_Exception",'Unable to write to the stream');
            }
        }
        else
        {
            Fynd_Object::throwException("Fynd_Log_Exception",'Unable to write to the stream,maybe it was closed.'.var_export($this->_stream,true));
        }
    }

    
}
?>