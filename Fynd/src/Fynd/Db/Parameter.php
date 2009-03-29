<?php
require_once 'Fynd/Object.php';
class Fynd_Db_Parameter extends Fynd_Object 
{
    const IN         = 30;
    const OUT        = 31;
    const IN_OUT     = 32;
    
	public $dataType;
	public $name;
	public $value;
	public $direction;
}
?>