<?php
require_once ('Fynd/Controller.php');
class DishCtrl extends Fynd_Controller
{
    private $_dishService;
    public function __construct()
    {
        $this->_dishService = Fynd_Service_Factory::createService(new Fynd_Type('DishService'));
    }
    
    public function indexAct(Fynd_Request $request)
    {
        $this->listAct($request);
    }
    
    public function listAct(Fynd_Request $request)
    {
        $view = new Fynd_View_Html(Fynd_Env::getViewPath() . 'DishListView.html');
        $view->setData($this->_dishService->getDishs()); 
        $this->setView($view);
    }
    public function editAct(Fynd_Request $request)
    {
        
    }
    public function showEditFormAct(Fynd_Request $request)
    {
        $view = new DishEditView();
        //$id = $request->getHttpParameter('id');
        $this->setView($view);
    }
    public function deleteAct(Fynd_Request $request)
    {
        
    }
}
?>