<?php
require_once ('src/Fynd/Controller.php');
class IndexCtrl extends Fynd_Controller
{
    public function indexAct()
    {
        $GLOBALS['IndexCtrl::indexAct'] = 'excuted';
    }
    public function testAct()
    {
        $GLOBALS['IndexCtrl::testAct'] = 'excuted';
    }
}
?>