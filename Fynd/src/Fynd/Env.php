<?php
require_once 'Fynd/Type.php';
/**
 * Describes the php script execute environment in a request.
 *
 */
final class Fynd_Env
{
    private function __construct()
    {
        parent::__construct();
    }
    private static $_basePath;
    private static $_appPath;
    private static $_ctrlPath;
    private static $_viewPath;
    private static $_modelPath;
    private static $_servicePath;
    private static $_configPath;
    private static $_logPath;
    /**
     * Gets the path of php script startup,it's usually to be the same as the index.php
     *
     * @return string
     */
    public static function getBasePath()
    {
        if(empty(self::$_basePath))
        {
            self::$_basePath = realpath('../') . "/";
        }
        return self::$_basePath;
    }
    /**
     * Gets the path of app,
     * the app directory contains the controllers,views,models,services,logs,and configs
     *
     * @return string
     */
    public static function getAppPath()
    {
        if(empty(self::$_appPath))
        {
            self::$_appPath = self::getBasePath() . "app/";
        }
        return self::$_appPath;
    }
    /**
     * Gets the path of controllers,
     * can be used in include sentence etc.
     *
     * @return string
     */
    public static function getCtrlPath()
    {
        if(empty(self::$_ctrlPath))
        {
            self::$_ctrlPath = self::getAppPath() . "controllers/";
        }
        return self::$_ctrlPath;
    }
    /**
     * Gets the path of views,
     * can be used in include sentence etc.
     *
     * @return string
     */
    public static function getViewPath()
    {
        if(empty(self::$_viewPath))
        {
            self::$_viewPath = self::getAppPath() . "views/";
        }
        return self::$_viewPath;
    }
    /**
     * The path of models,
     * can be used in include sentence etc.
     *
     * @return string
     */
    public static function getModelPath()
    {
        if(empty(self::$_modelPath))
        {
            self::$_modelPath = self::getAppPath() . "models/";
        }
        return self::$_modelPath;
    }
    /**
     * Gets the path of the services,
     * services contains the implimention of bussiness logic codes.
     *
     * @return string
     */
    public static function getServicePath()
    {
        if(empty(self::$_servicePath))
        {
            self::$_servicePath = self::getAppPath() . "services/";
        }
        return self::$_servicePath;
    }
    /**
     * Gets the path of configures,
     * can be used in include sentence etc.
     *
     * @return string
     */
    public static function getConfigPath()
    {
        if(empty(self::$_configPath))
        {
            self::$_configPath = self::getAppPath() . "configs/";
        }
        return self::$_configPath;
    }
    /**
     * Gets the path of logs
     *
     * @return string
     */
    public static function getLogPath()
    {
        if(empty(self::$_logPath))
        {
            self::$_logPath = self::getAppPath() . "logs/";
        }
        return self::$_logPath;
    }
    /**
     * Initialize the Fynd framework 
     *
     */
    public static function init()
    {
        //TODO:initialize Fynd_Env's log object.
        self::_registerAutoLoad();
        //set_error_handler(array('Fynd_Env' , 'phpErrorHandler'));
    }
    /**
     * Used by PHP autoload functionality
     *
     * @param string $class
     */
    public static function loadClassDefinition($class)
    {
        $type = new Fynd_Type($class);
        $type->includeTypeDefinition();
    }
    public static function phpErrorHandler($errorNo, $errorMsg, $errorFile = null, $errorLine = null)
    {
        switch($errorNo)
        {
            case E_COMPILE_ERROR:
            case E_CORE_ERROR:
            case E_ERROR:
            case E_PARSE:
            case 0:
                throw new ErrorException($errorMsg, 0, $errorNo, $errorFile, $errorLine);
            default:
                //throw new ErrorException($errorMsg, 0, $errorNo, $errorFile, $errorLine);
                break;
        }
    }
    /**
     * Register spl_autoload function
     *
     */
    private static function _registerAutoLoad()
    {
        if(! function_exists('spl_autoload_register'))
        {
            throw new Exception('spl_autoload does not exist in this PHP installation');
        }
        spl_autoload_register(array('Fynd_Env' , 'loadClassDefinition'));
    }
}
?>