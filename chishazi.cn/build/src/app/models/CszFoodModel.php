<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszFoodModel extends Fynd_Model
{
    private $_foodId;
    private $_foodCategoryId;
    private $_foodName;
    
    /**
     * @return number
     */
    public function getFoodId()
    {
        return $this->_foodId;
    }
    /**
     * @param number
     */
    public function setFoodId($_foodId)
    {
        $this->_foodId = $_foodId;
    }
    /**
     * @return number
     */
    public function getFoodCategoryId()
    {
        return $this->_foodCategoryId;
    }
    /**
     * @param number
     */
    public function setFoodCategoryId($_foodCategoryId)
    {
        $this->_foodCategoryId = $_foodCategoryId;
    }
    /**
     * @return string
     */
    public function getFoodName()
    {
        return $this->_foodName;
    }
    /**
     * @param string
     */
    public function setFoodName($_foodName)
    {
        $this->_foodName = $_foodName;
    }
}
?>