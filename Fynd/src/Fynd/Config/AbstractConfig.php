<?php
/**
 * 配置类的基类,主要完成如何读取配置文件和将配置项设置为类成员的任务
 *
 */
abstract class Fynd_Config_AbstractConfig
{
	/**
	 * 原始XML对象
	 *
	 * @var SimpleXMLElement
	 */
	protected $_config = null;
	/**
	 * 构造函数
	 *
	 * @param string $configXml
	 */
	public function __construct($configXml)
	{
		if(empty($configXml))
		{
			throw new Exception('Parameter $configXml can not be empty');
		}
		$this->_readXml($configXml);
		$this->_initConfig();
	}
	/**
    * 获取原始配置的SimpleXmlElement对象
    * @return  SimpleXMLElement
    */
	public function getRawConfig()
	{
		return $this->_config;
	}
	/**
	 * 读取XML配置文件
	 *
	 * @param String $xmlFilePath xml configure string
	 */
	protected function _readXml($configXml)
	{
		$this->_config = @simplexml_load_string($configXml);
		if($this->_config === false || is_null($this->_config)) 
		{
			throw new Exception('Unable load xml document object from given configure xml string');
		}
	}
	/**
    * 初始化配置
    * @return void
    */
	protected abstract function _initConfig();
}
?>