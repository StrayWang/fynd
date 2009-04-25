<?php
require_once ('Fynd/Controller.php');
require_once 'Fynd/Controller/IList.php';
class NutritionCtrl extends Fynd_Controller implements Fynd_Controller_IList 
{
    /**
     * @var NutritionService
     */
    private $_svcNutrition = null;
    public function __construct()
    {
        parent::__construct();
        $this->_svcNutrition = Fynd_Service_Factory::createService(new Fynd_Type('NutritionService'));
    }
    public function indexAct(Fynd_Request $request)
    {
        $this->listAct($request);
    }
    public function listAct(Fynd_Request $request)
    {
        //$data =$this->_svcNutrition->getModelList(20,0);
        $view = new Fynd_View_Html(Fynd_Env::getViewPath() . 'NutritionListView.html');
        //$view->setData($data);
        $this->setView($view);
    }
    /**
     * @see Fynd_Controller_IList::pagingListAct()
     *
     * @param Fynd_Request $request
     */
    public function pagingListAct(Fynd_Request $request)
    {
        $pageSize = intval($request->getHttpParameter('results'));
        
        $startOffset = intval($request->getHttpParameter('startIndex'));
        
        $orderby = $request->getHttpParameter('sort');
        $direction = $request->getHttpParameter('dir');
        
        $list = $this->_svcNutrition->getModelList($pageSize,$startOffset,$orderby,$direction);
        $count = $this->_svcNutrition->getModelCount();
        $data = new Fynd_UI_TableResult();
        $data->totalRecords = $count;
        $data->records = $list;

        $view = new Fynd_View_JSON();
        $view->setData($data);
        $this->setView($view);
    }

}
?>