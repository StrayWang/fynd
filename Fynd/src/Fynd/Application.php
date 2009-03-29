<?php
require_once 'Fynd/Object.php';
require_once 'Fynd/Env.php';
require_once 'Fynd/StringUtil.php';
require_once 'Fynd/Config/Manager.php';
require_once 'Fynd/Config/Type.php';
require_once 'Fynd/Request.php';
require_once 'Fynd/Log.php';
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
 * Fynd_Application::getInstance()->start();
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
class Fynd_Application extends Fynd_Object
{
    private static $_sessionStarted = false;
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
    
    private function __construct ()
    {}
    /**
     * Initialize application's execution environment,
     * reading global configures etc.
     *
     */
    public function init ()
    {
        Fynd_Env::init();
        Fynd_Cache::init();
        self::startSession();
        
        $logFile = Fynd_Env::getLogPath() . "system.log";
        $writer = new Fynd_Log_StreamWriter($logFile);
        $this->_log = new Fynd_Log($this->getType(), $writer);
        $this->_log->logInfo('Application initialized');
        
        
    }
    /**
     * Go go go!
     * Read request infomation,choose a proccessor to handle this request
     * and output the result.
     *
     */
    public function start ()
    {        
        $request = new Fynd_Request($_GET,$_POST);
        $ctrlName = $request->getControllerName();
        $actionName = $request->getActionName();

        $ctrlType = new Fynd_Type($ctrlName);
        $ctrlInstance = $ctrlType->createInstance();
        
        try
        {
            $ctrlInstance->$actionName($request);
        }
        catch(Exception $e)
        {
            header("HTTP/1.1 500");
            echo $e->getMessage();
            echo str_replace("#","\n#",$e->getTraceAsString());
            //throw $e;
        }
        $this->stop(false);
    }
    /**
     * Force stop the php application running.
     *
     * @param bool $force If FALSE passed,it is not really call exit() to stop executing,
     * then if you want to the application run correctly,init() method must be called agin.
     */
    public function stop ($force = true)
    {
        if ($this->_log)
        {
            $this->_log->log("Script end.", Fynd_Log::LOG_INFO);
            $this->_log = null;
        }
        if(true === $force)
        {
            exit();
        }
    }
    /**
     * Start the http session records.
     *
     */
    public static function startSession ()
    {
        if (! self::$_sessionStarted)
        {
            $fail = @session_start();
            if(false !== $fail)
            {
                self::$_sessionStarted = true;
            }
        }
    }
    /**
     * Get the identity that identify whether session was started or not
     *
     * @return bool
     */
    public static function getSessionStarted ()
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
        $logConfig = Fynd_Config_Manager::getConfig(Fynd_Config_Type::LOG_CONFIG);
        $writer = new Fynd_Log_StreamWriter($logConfig->getDefaultLogFile());
        $logger = new Fynd_Log($loggerName, $writer);
        return $logger;
    }
}
?>