<?php
//include_once 'Controller.php';
class IndexCtrl extends Fynd_Controller 
{
	public function indexAct()
	{
		$this->_selectView('TopicList',Fynd_Controller::JSON_VIEW);
	}
}
?>