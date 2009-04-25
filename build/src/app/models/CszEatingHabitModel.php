<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszEatingHabitModel extends Fynd_Model
{
    private $_eatingHabitId;
    
    /**
     * @return number
     */
    public function getEatingHabitId()
    {
        return $this->_eatingHabitId;
    }
    /**
     * @param number
     */
    public function setEatingHabitId($_eatingHabitId)
    {
        $this->_eatingHabitId = $_eatingHabitId;
    }
}
?>