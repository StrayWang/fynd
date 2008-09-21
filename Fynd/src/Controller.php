<?php
class Fynd_Controller
{
	protected  $_responseType;
	protected function _redirect($ctrl,$act,$type)
	{
		$act = empty($act) ? 'index' : $act;
		$type = empty($type) ? $this->_responseType : $type;
		$header = "location:index.php?c=$ctrl&a=$act&t=$type";
		header($header);
	}
	protected function _selectView($view,$type = null)
	{
		ob_start();
		if(empty($type) || $type == self::HTML_VIEW)
		{
			include 'views/'.$view.'.php';
		}
		else if($type == self::JSON_VIEW)
		{
			header("content-type:text/plain");
			include_once "views/$view".'.php';
			$view = new $view();
			$data = $view->render();
			echo $data;
		}
		ob_end_flush();
	}
	public function __call($act,$param)
	{
		echo "$act has nerver been defined";
		var_dump($param);
	}
	const JSON_VIEW = 1;
	const HTML_VIEW = 2;
	const XML_VIEW = 3;
}
?>