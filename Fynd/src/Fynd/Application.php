<?php
require_once 'PublicPropertyClass.php';
require_once 'RequestHandler.php';
require_once 'Log.php';
require_once 'Log/Writer/Stream.php';
/**
 * This is global object of whole application,
 * it dispatch request,hold global variables.
 * it will be only single instance of lifetime.
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
    protected $_workPath = '';
    /**
     * logger
     *
     * @var Fynd_Log
     */
    protected $_log = null;
    /**
     * single instance of Fynd_Application
     *
     * @var Fynd_Application
     */
    private static $_instance = null;
    /**
     * single instance factory
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
     * Get path of application's controllers.
     *
     * @return string
     */
    public static function getCtrlPath ()
    {
        return self::getInstance()->CtrlPath;
    }
    /**
     * Get path of application's models.
     *
     * @return string
     */
    public static function getModelPath ()
    {
        return self::getInstance()->ModelPath;
    }
    /**
     * Get path of application's views.
     *
     * @return string
     */
    public static function getViewPath ()
    {
        return self::getInstance()->ViewPath;
    }
    /**
     * Get path of application's configures.
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
        if(empty($this->_workPath))
        {
            $this->_workPath =realpath('../')."/"; 
        }
        return $this->_workPath; 
    }
    /**
     * Initialize application's execution environment,
     * read global configures etc.
     *
     */
    public function init ()
    {
        $this->_ctrlPath = $this->getAppWorkPath() . 'app/controllers/';
        $this->_modelPath = $this->getAppWorkPath() . 'app/models/';
        $this->_viewPath = $this->getAppWorkPath() . 'app/views/';
        $this->_configPath = $this->getAppWorkPath() . 'app/configs/';
        $this->_log = new Fynd_Log();
        $logFile = $this->getAppWorkPath()."log/debug.log";
        $writer = new Fynd_Log_Writer_Stream($logFile);
        $this->_log->addWriter($writer);
        $this->_log->log("Logger created",Fynd_Log::INFO);
    }
    /**
     * Go go go!
     * read request infomation,choose a proccessor to handle this request
     * and output result.
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
    private function _stop()
    {
        $this->_log->log("Script end!!!",Fynd_Log::INFO);
        $this->_log = null;
    }
    public static function startSession ()
    {
        if (! self::$_sessionStarted)
        {
            session_start();
            self::$_sessionStarted = true;
        }
    }
    public static function logInfo($msg)
    {
        self::getInstance()->logInfomationMsg($msg);
    }
    public static function logWarn($msg)
    {
        self::getInstance()->logWarnMsg($msg);
    }
    public static function logError($msg)
    {
        self::getInstance()->logErrorMsg($msg);
    }
    public function logInfomationMsg($msg)
    {
        $this->_log->log($msg,Fynd_Log::INFO);
    }
    public function logWarnMsg($msg)
    {
        $this->_log->log($msg,Fynd_Log::WARN);
    }
    public function logErrorMsg($msg)
    {
        $this->_log->log($msg,Fynd_Log::ERR);
    }
    /**
     * Load class defintion file from $class parameter
     *
     * @param string $class
     */
    public function loadClass ($class)
    {
        if (! Fynd_Util::startWith($class, 'Fynd_') && Fynd_Util::endWith($class, "Ctrl"))
        {
            include_once self::getCtrlPath() . $class . ".php";
        }
        else if (! Fynd_Util::startWith($class, 'Fynd_') && Fynd_Util::endWith($class, "View"))
        {
            include_once self::getViewPath() . $class . ".php";
        }
        else if (! Fynd_Util::startWith($class, 'Fynd_') && Fynd_Util::endWith($class, "Model"))
        {
            include_once self::getModelPath() . $class . ".php";
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
            include_once $file;
        }
        if (! class_exists($class))
        {
            throw new Exception('Include failed ' . $file);
        }
    }
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