<?php
require_once ('Fynd/Controller.php');
class PickerCtrl extends Fynd_Controller
{
    public function nutritionAct(Fynd_Request $request)
    {
        $svc = Fynd_Service_Factory::createService(new Fynd_Type('NutritionService'));
        $pageSize = intval($request->getHttpParameter('results'));
        
        $startOffset = intval($request->getHttpParameter('startIndex'));
        
        $orderby = $request->getHttpParameter('sort');
        $direction = $request->getHttpParameter('dir');
        
        $list = $svc->getModelList($pageSize,$startOffset,$orderby,$direction);
        $count = $svc->getModelCount();
        $data = new Fynd_UI_TableResult();
        $data->totalRecords = $count;
        $data->records = $list;

        $view = new Fynd_View_JSON();
        $view->setData($data);
        $this->setView($view);
    }
}
?>