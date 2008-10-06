<?php
/**
 * Controller base class in MVC
 *
 */
class Fynd_Controller
{
    /**
     * Redirect to anothor controller
     *
     * @param string $ctrl
     * @param string $act
     */
	protected function _redirect($ctrl,$act)
	{
		$act = empty($act) ? 'index' : $act;
		$header = "location:index.php?c=$ctrl&a=$act";
		header($header);
	}
	/**
	 * Choose a view
	 *
	 * @param string $view
	 * @param int $type 
	 */
	protected function _selectView($view,$type = null)
	{
		ob_start();
		if(empty($type) || $type == self::HTML_VIEW)
		{
			include Fynd_Application::getViewPath().$view.'.php';
		}
		else if($type == self::JSON_VIEW)
		{
			header("content-type:text/plain");
			include_once Fynd_Application::getViewPath().$view.'.php';
			$view = new $view();
			$data = $view->render();
			echo $data;
		}
		ob_end_flush();
	}
	/**
	 * Default method will be called when request method does not exsisted 
	 *
	 * @param string $act
	 * @param array $param
	 */
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