<?php
require_once 'PublicPropertyClass.php';
require_once 'RequestHandler.php';
class Fynd_Application extends Fynd_PublicPropertyClass
{
    protected $_ctrlPath = '';
    protected $_viewPath = '';
    protected $_modelPath = '';
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
    public static function getCtrlPath ()
    {
        return self::getInstance()->CtrlPath;
    }
    public static function getModelPath ()
    {
        return self::getInstance()->ModelPath;
    }
    public static function getViewPath ()
    {
        return self::getInstance()->ViewPath;
    }
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
        return realpath('../');
    }
    public function init ()
    {
        $this->_ctrlPath = $this->getAppWorkPath() . 'app/controllers/';
        $this->_modelPath = $this->getAppWorkPath() . 'app/models/';
        $this->_viewPath = $this->getAppWorkPath() . 'app/views/';
        $this->_configPath = $this->getAppWorkPath() . 'app/configs/';
    }
    public function run ()
    {
        $ctrl = Fynd_RequestHandler::GetControllerName();
        $action = Fynd_RequestHandler::GetControllerAction();
        $ctrl .= 'Ctrl';
        $action .= 'Act';
        include ($this->_ctrlPath . $ctrl . '.php');
        $ctrlInstance = new $ctrl();
        $ctrlInstance->$action();
    }
}
?>