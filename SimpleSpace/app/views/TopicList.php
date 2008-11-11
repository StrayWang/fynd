<?php
include_once 'JsonView.php';
include_once 'Model/ModelSelection.php';
include_once Fynd_Application::getModelPath().'Topic.php';
class TopicList extends Fynd_JsonView 
{
	public function __construct()
	{
		//$selection = new Fynd_Model_ModelSelection();
		
		$model = new Topic();
		$models = $model->select(array());
		$this->_data = $models;
	}
}
?>