<?php
require_once('Fynd/Config/AbstractConfig.php');
/**
 * 请求映射配置类
 *
 */
class Fynd_Config_MapConfig extends Fynd_Config_AbstractConfig
{
    /**
     * @param Array $requestConfigMap
     */
	protected function setMapProperty(Array $requestConfigMap)
	{
		//如果请求模块名为空或未设置,则发出警告错误,使用日志记录
		if(isset($requestConfigMap['requestModule']) && !empty($requestConfigMap['requestModule']))
		{
			$this->$requestConfigMap['requestModule'] = (empty($requestConfigMap['processorName']))
				? 'Index' 
				: (string)$requestConfigMap['processorName'];
		}
		else 
		{
			//TODO:文件不是针对该配置类的配置文件,记录为警告级别错误,使用日志记录
		}
	}
}
?>