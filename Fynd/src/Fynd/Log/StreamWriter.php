<?php
require_once 'Fynd/PublicPropertyClass.php';
require_once 'IWriter.php';
/**
 * Stream writer of log object,write the log message to stream
 *
 */
class Fynd_Log_StreamWriter extends Fynd_PublicPropertyClass implements Fynd_Log_IWriter 
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
                throw new Exception('Can not open log stream,$streamOrFile = '.$streamOrFile);
            }
        }
        
        if(get_resource_type($this->_stream) != 'stream')
        {
            throw new Exception('Resource is not a stream');
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
                throw new Exception('Unable to write to the stream');
            }
        }
        else
        {
            throw new Exception('Unable to write to the stream,maybe it was closed.'.var_export($this->_stream,true));
        }
    }

    
}
?>