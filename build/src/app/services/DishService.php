<?php
require_once ('Fynd/Service.php');
class DishService extends Fynd_Service
{
    function __construct()
    {}
    /**
     * @see Fynd_Service::init()
     *
     */
    public function init()
    {
        parent::init();
    }
    
    public function getDishs()
    {
        return Fynd_Model_Factory::getModels(new Fynd_Type('CszDishModel'));
    }

}
?>