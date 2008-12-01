<?php
require_once ('src/Fynd/Controller.php');
class TestCtrl extends Fynd_Controller
{
    public function indexAct()
    {
        $GLOBALS['TestCtrl::indexAct'] = "excuted";
    }
    public function testAct()
    {
        $GLOBALS['TestCtrl::testAct'] = 'excuted';
    }
}
?>