<?php
require_once ('Fynd/Object.php');
class Fynd_UI_TableResult extends Fynd_Object
{
    /**
     * Reponse data.
     * 
     * @var Fynd_List
     */
    public $records;
    /**
     * 
     * total rows num.
     */
    public $totalRecords;
}
?>