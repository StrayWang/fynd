<?php
require_once 'PublicPropertyClass.php';
require_once 'RequestHandler.php';
/**
 * This is global object of whole application,
 * it dispatch request,hold global variables.
 * it will be only single instance of lifetime.
 *
 */
class Fynd_Application extends Fynd_PublicPropertyClass
{
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
        return realpath('../').'/';
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
    }
    /**
     * Go go go!
     * read request infomation,choose a proccessor to handle this request
     * and output result.
     *
     */
    public function run ()
    {
        $ctrl = Fynd_RequestHandler::GetControllerName();
        $action = Fynd_RequestHandler::GetControllerAction();
        $ctrl .= 'Ctrl';
        $action .= 'Act';
        include ($this->_ctrlPath . $ctrl . '.php');
        $ctrlInstance = new $ctrl();
        $ctrlInstance->$action($_REQUEST);
    }
}
?>