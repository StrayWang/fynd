<?php
include_once 'IView.php';
class Fynd_JsonView implements Fynd_IView 
{
	protected $_data;
	public function render()
	{
		return Fynd_Util::jsonEncode($this->_data);
	}
}
?>