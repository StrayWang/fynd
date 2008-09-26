<?php
/**
 * 配置管理器,提供创建所有配置类实例的接口
 * 
 * @author Fishtrees
 * @version 2008-2-3
 **/
final class Fynd_Config_ConfigManager
{
	/**
	 * 默认配置文件目录
	 *
	 * @var string
	 */
	private static $DefaultConfigpPath = "Configs/";//TODO:默认配置文件应该从配置文件读取
	private function __construct(){}
	/**
	 * 获取配置类实例
	 *
	 * @param String $configType 指定要获取的配置类型
	 * @param String $configFilePath 配置文件路径
	 * @return Fynd_Config_AbstractConfig
	 */
	public static function getConfig($configType,$configFilePath = "")
	{
		if(empty($configFilePath))
		{
			$configFilePath = self::$DefaultConfigpPath . $configType . '.xml';
		}
		$config = null;
		if(!empty($configType))
		{
			$configClassName = "Fynd_Config_" . $configType;
			include_once($configType.".php");
			$config = new $configClassName($configFilePath);
		}
		else
		{
			throw new Exception('Undefined configure type,can not load configure file');
		}
		return $config;
	}
}
?>