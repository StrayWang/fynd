<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszFoodInDishModel extends Fynd_Model
{
    private $_foodInDishId;
    private $_foodNutritionId;
    private $_dishId;
    
    /**
     * @return number
     */
    public function getFoodInDishId()
    {
        return $this->_foodInDishId;
    }
    /**
     * @param number
     */
    public function setFoodInDishId($_foodInDishId)
    {
        $this->_foodInDishId = $_foodInDishId;
    }
    /**
     * @return number
     */
    public function getFoodNutritionId()
    {
        return $this->_foodNutritionId;
    }
    /**
     * @param number
     */
    public function setFoodNutritionId($_foodNutritionId)
    {
        $this->_foodNutritionId = $_foodNutritionId;
    }
    /**
     * @return number
     */
    public function getDishId()
    {
        return $this->_dishId;
    }
    /**
     * @param number
     */
    public function setDishId($_dishId)
    {
        $this->_dishId = $_dishId;
    }
}
?>