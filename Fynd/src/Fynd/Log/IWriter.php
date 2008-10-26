<?php
interface Fynd_Log_IWriter
{
    /**
     * Write mssage to the stream,database,or any container which can be used to store data
     *
     * @param string $msg
     */
    public function write($msg);
    /**
     * Close the data container
     *
     */
    public function close();
}
?>