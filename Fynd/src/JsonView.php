<?php
include_once 'View.php';
class Fynd_JsonView extends Fynd_View 
{
	protected $_data;
	public function render()
	{
	    $this->setMimeType('text/plain');
		return Fynd_Util::jsonEncode($this->_data);
	}
}
?>