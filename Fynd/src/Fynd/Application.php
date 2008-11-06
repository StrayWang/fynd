<?php
require_once 'PublicPropertyClass.php';
require_once 'Fynd/Config/ConfigManager.php';
require_once 'Fynd/Config/ConfigType.php';
require_once 'RequestHandler.php';
require_once 'Log.php';
require_once 'Fynd/Log/StreamWriter.php';
/**
 * This class describe your application,
 * it dispatch request,hold global variables,watch status of application,etc.
 * There are serval methods to help you run your application,simply,just call run method
 *  to start the application,and if your want to use log,get the logger through calling 
 * getLogger method.
 * It will be only single instance of lifetime.
 * <code>
 * <?php
 * require 'Fynd/Application.php';
 * //Simply,run your application like this:
 * Fynd_Application::getInstance()->run();
 *
 * //Get Log like this:
 * $logger = Fynd_Application::getLogger('root');
 * $logger->logInfo('I am a logger!'');
 * ?>
 * </code>
 * @package Fynd
 * @author FishTrees
 *
 */
class Fynd_Application extends Fynd_PublicPropertyClass
{
    private static $_sessionStarted = false;
    /**
     * The path of controllers,
     * accessed by $CtrlPath property of Fynd_Application instance, or getCtrlPath static method
     * can be used in include sentence etc.
     *
     * @var string
     */
    protected $_ctrlPath = '';
    /**
     * The path of views,
     * accessed by $ViewPath property of Fynd_Application instance or getViewPath static method
     * can be used in include sentence etc.
     *
     * @var string
     */
    protected $_viewPath = '';
    /**
     * The path of models,
     * accessed by $ModelPath property of Fynd_Application instance, or getModelPath static method
     * can be used in include sentence etc.
     *
     * @var string
     */
    protected $_modelPath = '';
    /**
     * The path of configures,
     * accessed by $ConfigPath property of Fynd_Application instance,or getConfigPath static method
     * can be used in include sentence etc.
     *
     * @var string
     */
    protected $_configPath = '';
    /**
     * The path of php script startup.
     *
     * @var string
     */
    protected $_workPath = '';
    /**
     * The logger, it is used by Fynd_Application instance only.
     *
     * @var Fynd_Log
     */
    protected $_log = null;
    /**
     * Single instance of Fynd_Application
     *
     * @var Fynd_Application
     */
    private static $_instance = null;
    /**
     * Single instance factory,only way to get @see Fynd_Application instance.
     * @return Fynd_Application
     */
    public static function getInstance ()
    {
        if (! self::$_instance instanceof Fynd_Application)
        {
            self::$_instance = new Fynd_Application();
            self::$_instance->init();
        }
        return self::$_instance;
    }
    /**
     * Get the path of application's controllers.
     *
     * @return string
     */
    public static function getCtrlPath ()
    {
        return self::getInstance()->CtrlPath;
    }
    /**
     * Get the path of application's models.
     *
     * @return string
     */
    public static function getModelPath ()
    {
        return self::getInstance()->ModelPath;
    }
    /**
     * Get the path of application's views.
     *
     * @return string
     */
    public static function getViewPath ()
    {
        return self::getInstance()->ViewPath;
    }
    /**
     * Get the path of application's configures.
     *
     * @return string
     */
    public static function getConfigPath ()
    {
        return self::getInstance()->ConfigPath;
    }
    private function __construct ()
    {}
    /**
     * Get directory which application work in,end with '/'
     *
     * @return string
     */
    public function getAppWorkPath ()
    {
        if (empty($this->_workPath))
        {
            $this->_workPath = realpath('../');
        }
        return $this->_workPath;
    }
    /**
     * Initialize application's execution environment,
     * reading global configures etc.
     *
     */
    public function init ()
    {
        $this->_ctrlPath = $this->getAppWorkPath() . 'app/controllers/';
        $this->_modelPath = $this->getAppWorkPath() . 'app/models/';
        $this->_viewPath = $this->getAppWorkPath() . 'app/views/';
        $this->_configPath = $this->getAppWorkPath() . 'app/configs/';
        try
        {
            //TODO:Read log configure use LogConfig object
            $logFile = $this->getAppWorkPath() . "log/system.log";
            $writer = new Fynd_Log_StreamWriter($logFile);
            $this->_log = new Fynd_Log($this->getType(), $writer);
            $this->_log->logInfo('Application started');
        }
        catch (Exception $e)
        {
            return $e;
        }
    }
    /**
     * Go go go!
     * Read request infomation,choose a proccessor to handle this request
     * and output the result.
     *
     */
    public function run ()
    {
        $this->_registerAutoLoad();
        self::startSession();
        $ctrl = Fynd_RequestHandler::GetControllerName();
        $action = Fynd_RequestHandler::GetControllerAction();
        $ctrl .= 'Ctrl';
        $action .= 'Act';
        include ($this->_ctrlPath . $ctrl . '.php');
        $ctrlInstance = new $ctrl();
        $ctrlInstance->$action($_REQUEST);
        $this->_stop();
    }
    private function _stop ()
    {
        if ($this->_log)
        {
            $this->_log->log("Script end!!!", Fynd_Log::LOG_INFO);
            $this->_log = null;
        }
    }
    public static function startSession ()
    {
        if (! self::$_sessionStarted)
        {
            @session_start();
            self::$_sessionStarted = true;
        }
    }
    /**
     * Get the identity that identify whether session was started or not
     *
     * @return bool
     */
    public static function getIsSessionStarted ()
    {
        return self::$_sessionStarted;
    }
    /**
     * Get a logger, which name is specifid by parameter $name,
     * this method will read LogConfig.xml by default to determine the log file path.
     * Recommend using class name,or method name for the parameter $name. 
     *
     * @param string $loggerName the name of logger
     * @return Fynd_Log
     */
    public static function getLogger ($loggerName)
    {
        //TODO:Use a reader object to read configure string from configure files.
        $configXml = file_get_contents(self::getConfigPath() . 'LogConfig.xml');
        $logConfig = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::LogConfig, $configXml);
        $writer = new Fynd_Log_StreamWriter($logConfig->getDefaultLogFile());
        $logger = new Fynd_Log($loggerName, $writer);
        return $logger;
    }
    /**
     * Load class defintion file from $class parameter
     *
     * @param string $class which class will be loaded
     */
    public function loadClass ($class)
    {
        if (! Fynd_Util::startWith($class, 'Fynd_') && Fynd_Util::endWith($class, "Ctrl"))
        {
            @include_once self::getCtrlPath() . $class . ".php";
        }
        else if (! Fynd_Util::startWith($class, 'Fynd_') && Fynd_Util::endWith($class, "View"))
        {
            @include_once self::getViewPath() . $class . ".php";
        }
        else if (! Fynd_Util::startWith($class, 'Fynd_') && Fynd_Util::endWith($class, "Model"))
        {
            @include_once self::getModelPath() . $class . ".php";
        }
        else
        {
            $file = str_replace('Fynd_', '', $class);
            $parts = split('_', $file);
            if (is_array($parts) && count($parts) > 1)
            {
                $file = "";
                for ($i = 0; $i < count($parts) - 1; $i ++)
                {
                    $file .= $parts[$i] . '/';
                }
                $file .= $parts[count($parts) - 1] . '.php';
            }
            else
            {
                $file = $file . '.php';
            }
            @include_once $file;
        }
        if (! class_exists($class))
        {
            throw new Exception($class . '\'s definition file can not be loaded,it is ' . $file);
        }
    }
    /**
     * Used PHP autoload functionality
     *
     * @param string $class
     */
    public static function autoload ($class)
    {
        self::getInstance()->loadClass($class);
    }
    /**
     * Register spl_autoload function
     *
     */
    private function _registerAutoLoad ()
    {
        if (! function_exists('spl_autoload_register'))
        {
            throw new Exception('spl_autoload does not exist in this PHP installation');
        }
        spl_autoload_register(array('Fynd_Application' , 'autoload'));
    }
}
?>