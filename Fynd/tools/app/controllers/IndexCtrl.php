<?php
require_once 'Fynd/Controller.php';
require_once 'Fynd/Service/Factory.php';
class IndexCtrl extends Fynd_Controller
{
    /**
     * @var Fynd_Log
     */
    private static $_log = null;
    /**
     * @var ModelCreationService
     */
    private $_svcModelCreation;
    public function __construct()
    {
        if(is_null(self::$_log))
        {
            self::$_log = Fynd_Application::getLogger("IndexCtrl");
        }
        
        $this->_svcModelCreation = Fynd_Service_Factory::createService(new Fynd_Type("ModelCreationService"));
    }
    public function indexAct ()
    {}
    public function showTablesAct (Fynd_Request $request)
    {
        $dataObj = Fynd_JSON::decode($request->getHttpPostVar("data"));
        $dbModel = Fynd_Model_Factory::createModel(new Fynd_Type("DatabaseModel"),$dataObj);
        $dbModel->setDatabaseType(Fynd_Db_Type::MYSQL);
        $tables = $this->_svcModelCreation->getTableList($dbModel);
        
        $view = new Fynd_View_JSON();
        $view->setData($tables);
        
        $this->setView($view);
    }
    public function createModelMapAct (Fynd_Request $request)
    {
        self::$_log->logInfo($request->getHttpPostVar("data"));
        $dataObj = Fynd_JSON::decode($request->getHttpPostVar("data"));
        self::$_log->logInfo($dataObj);
        $tableModel = Fynd_Model_Factory::createModel(new Fynd_Type("TableModel"),$dataObj->table->value);
        $dbModel = Fynd_Model_Factory::createModel(new Fynd_Type("DatabaseModel"),$dataObj->database->value);
        $dbModel->setDatabaseType(Fynd_Db_Type::MYSQL);
        
        $result = $this->_svcModelCreation->createFyndModelMap($tableModel,$dbModel);
        
        $view = new DownloadModelMapView();
        $view->setData($result);
        
        $this->setView($view);
    }
    public function createAllModelMapAct(Fynd_Request $request)
    {
        $dataObj = Fynd_JSON::decode($request->getHttpPostVar('data'));
        
        $dbModel = Fynd_Model_Factory::createModel(new Fynd_Type('DatabaseModel'),$dataObj);
        $dbModel->setDatabaseType(Fynd_Db_Type::MYSQL);

        $result = $this->_svcModelCreation->createAllFyndModelMap($dbModel);
        
        $view = new DownloadModelMapView();
        $view->setData($result);
        
        $this->setView($view);
    }
}
?>