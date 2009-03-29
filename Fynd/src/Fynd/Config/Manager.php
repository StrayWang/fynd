<?php
/**
 * 配置管理器,提供创建所有配置类实例的接口
 * 
 * @author Fishtrees
 * @version 2008-2-3
 **/
final class Fynd_Config_Manager
{
    /**
     * @var string
     */
    private static $defaultConfigpPath = "configs/";
    private static $defaultDbConfigPath = "DbConfig.xml";
    private static $defaultLogConfigPath = "MapConfig.xml";
    
    private function __construct ()
    {}
    /**
     * Gets configure object
     *
     * @param String $configType 由Fynd_Config_Type指定的要获取的配置类型
     * @param String $configXmlPath
     * @return Fynd_Config_AbstractConfig
     */
    public static function getConfig ($configType, $configXmlPath = "")
    {
        if (empty($configType))
        {
            Fynd_Object::throwException("Fynd_Config_Exception",'$configType can not be null or empty');
        }
        if(empty($configXmlPath))
        {
            switch ($configType) 
            {
            	case Fynd_Config_Type::DB_CONFIG:
            	    $configXmlPath = self::$defaultConfigpPath . self::$defaultDbConfigPath;
            	    break; 
            }
        }
        $configClassName = "Fynd_Config_" . $configType;
        $config = Fynd_Cache::fetch($configClassName);
        if(false === $config || is_null($config))
        {
            $type = new Fynd_Type($configClassName);
            $type->includeTypeDefinition();
            $config = new $configClassName($configXmlPath);
            Fynd_Cache::add($configClassName,$config,0);
        }
        return $config;
    }
}
?>