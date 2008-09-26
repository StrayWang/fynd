<?php
require_once 'Util.php';
class Fynd_RequestHandler
{
	public static function GetControllerName()
	{
		$ctrl = trim(strtolower($_GET['c']));
		unset($_GET['c']);
		$ctrl = Fynd_Util::upperCaseFirstChar($ctrl);
		if(empty($ctrl))
			$ctrl = 'Index';			
		return $ctrl;
	}
	public static function GetControllerAction()
	{
		$action = strtolower($_GET['a']);
		unset($_GET['a']);
		if(empty($action))
			$action = 'Index';	
		return $action;
	}
	public  static function GetRequestType()
	{
		$type = strtolower($_GET['t']);
		unset($_GET['t']);
		return $type;
	}
}
?>