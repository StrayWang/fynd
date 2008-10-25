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
	 * 配置文件路径
	 *
	 * @var string
	 */
	protected $_configFilePath;
	/**
	 * 构造函数
	 *
	 * @param string $configFilePath
	 */
	public function __construct($configFilePath)
	{
		if(empty($configFilePath))
		{
			throw new Exception('Empty value of parameter $configFilePath that can not be empty');
		}
		$this->_configFilePath = $configFilePath;
		$this->_readXml($configFilePath);
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
	 * @param String $xmlFilePath Xml文件路径
	 */
	protected function _readXml($xmlFilePath)
	{
		$this->_config = @simplexml_load_file($xmlFilePath);
		if($this->_config === false || is_null($this->_config)) 
		{
			throw new Exception('Unable read the configure file or it doesn\'t exsit');
		}
	}
	/**
    * 初始化配置
    * @return void
    */
	protected abstract function _initConfig();
}
?>