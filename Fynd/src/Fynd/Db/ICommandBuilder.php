<?php
interface Fynd_Db_ICommandBuilder
{
    /**
     * Build a Fynd_DB_ICommand object
     * @return Fynd_DB_ICommand
     *
     */
    public function createCommand();
}
?>