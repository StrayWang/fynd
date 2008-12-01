<?php
include_once 'Fynd/Controller.php';
include_once Fynd_Application::getModelPath(). 'User.php';
class RegisterCtrl extends Fynd_Controller
{
    public function indexAct()
    {
        $user = new User();
        $user->setPassport('yulin12');
        $user->setPassword('123456');
        $user->setEmail('yulin12@fynd.org');
        $user->save();
        
        $sel = new Fynd_Model_ModelSelection();
        $sel->Property = 'Passport';
        $sel->ConditionValue = 'yulin12';
        $user = $user->select($sel);
        var_dump($user->getIterator());
        if($user)
        {
            var_dump($user);
            $user->FirstName = 'YuLin';
            $user->Password = '333333';
            $user->save();
            $user = $user->select($sel);
            var_dump($user->getIterator());
            $user->delete();
        }
        
    }
}
?>