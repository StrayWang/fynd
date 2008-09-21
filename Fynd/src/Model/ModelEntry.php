<?php
class Fynd_Model_ModelEntry extends Fynd_PublicPropertyClass
{
	protected $_property;
	protected $_field;
	protected $_dataType;
	protected $_dataLength;
	
	public function getProperty() {
		return $this->_property;
	}
	public function getField() {
		return $this->_field;
	}
	public function getDataType() {
		return $this->_dataType;
	}
	public function getDataLength() {
		return $this->_dataLength;
	}
}
?>