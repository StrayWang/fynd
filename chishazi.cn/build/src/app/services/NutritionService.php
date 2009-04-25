<?php
require_once ('Fynd/Service.php');
require_once 'Fynd/Service/IList.php';
class NutritionService extends Fynd_Service implements Fynd_Service_IList
{
    function __construct()
    {}
    /**
     * @see Fynd_Service_IList::getModelList()
     *
     * @param int $pageSize
     * @param int $startOffset
     * @param string $orderby;
     * @param string $direction
     * @param string $filter
     * @return Fynd_Model_List
     */
    public function getModelList($pageSize, $startOffset, $orderby = '', $direction = 'asc', $filter = '')
    {
        if($pageSize <= 0)
        {
            $pageSize = 20;
        }
        if($startOffset < 0)
        {
            $startOffset = 0;
        }
        if($direction != 'asc' && $direction != 'desc')
        {
            $direction = 'asc';
        }
        return Fynd_Model_Factory::getModels(new Fynd_Type('CszNutritionModel'), $filter, $pageSize, $startOffset, $orderby, $direction);
    }
    /**
     * @see Fynd_Service_IList
     *
     * @param string $filter
     * @return number
     */
    public function getModelCount($filter = '')
    {
        return Fynd_Model_Factory::getModelCount(new Fynd_Type('CszNutritionModel'), $filter);
    }
}
?>