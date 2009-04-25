<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszFoodNutritionModel extends Fynd_Model
{
    private $_foodNutritionId;
    private $_nutritionId;
    private $_foodId;
    private $_nutritionValue;
    
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
    public function getNutritionId()
    {
        return $this->_nutritionId;
    }
    /**
     * @param number
     */
    public function setNutritionId($_nutritionId)
    {
        $this->_nutritionId = $_nutritionId;
    }
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
     * @return string
     */
    public function getNutritionValue()
    {
        return $this->_nutritionValue;
    }
    /**
     * @param string
     */
    public function setNutritionValue($_nutritionValue)
    {
        $this->_nutritionValue = $_nutritionValue;
    }
}
?>