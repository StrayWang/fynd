<?php
include_once 'RequestHandler.php';
final class Fynd_Application
{
	private static $_instance = null;
	/**
	 *
	 * @return Fynd_Application
	 */
	public static function GetInstance()
	{
		if(!self::$_instance instanceof Fynd_Application)
			self::$_instance = new Fynd_Application();
		return self::$_instance;	
	}
	
	public function Run()
	{
		$ctrl = Fynd_RequestHandler::GetControllerName();
		$action = Fynd_RequestHandler::GetControllerAction();
		$ctrl .= 'Ctrl';
		$action .= 'Act';
		
		include('controllers/'.$ctrl.'.php');
		$ctrlInstance = new $ctrl();
		$ctrlInstance->$action();
	}
}
?>