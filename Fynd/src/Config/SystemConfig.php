<?php
/**
 * 系统配置,该类型用于描述Fynd系统使用的基础配置
 *
 */
class Fynd_Config_SystemConfig extends Fynd_Config_AbstractConfig
{
	/**
	 * 默认的配置文件路径
	 *
	 */
	const DefaultConfigFile = "SystemConfig.xml";
	/**
	 * 创建系统配置
	 *
	 * @param String $filePath 配置文件路径,如果该参数为空,则读取默认的配置文件
	 */
	public function __construct($filePath = "")
	{
		if(empty($filePath))
		{
			$filePath = DefaultConfigFile;
		}
		parent::__construct($filePath);
	}
}
?>