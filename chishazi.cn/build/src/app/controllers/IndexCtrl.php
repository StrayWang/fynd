<?php
require_once ('Fynd/Controller.php');
class IndexCtrl extends Fynd_Controller
{
    public function indexAct()
    {
        $this->_redirect('Dish','List');
    }
}
?>