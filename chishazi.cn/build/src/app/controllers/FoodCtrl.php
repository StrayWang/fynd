<?php
require_once ('Fynd/Controller.php');
class FoodCtrl extends Fynd_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function indexAct()
    {}
    
    public function editAct()
    {
        $view = new FoodEditView();
        //$view->setData($data);
        $this->setView($view);
    }
}
?>