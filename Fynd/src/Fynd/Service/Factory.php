<?php
require_once ('Fynd/Object.php');
//require_once 'Fynd/Security.php';
final class Fynd_Service_Factory extends Fynd_Object
{
	/**
	 * @param    Fynd_Type $serviceType
     * @param    string $passport    
     * @return   Fynd_Service
     */
    public static function createService(Fynd_Type $serviceType,$passport = "")
    {
//        $user = Fynd_Security::getCurrentUser();
//        if(!empty($passport))
//        {
//            $user = Fynd_Security::getUser($passport);
//        }
        
        $svc = $serviceType->createInstance();
        //$svc->setUser($user);
        $svc->setDefaultDbConnection(Fynd_Db_Factory::getConnection());
        $svc->init();
        return $svc;
    }
}
?>