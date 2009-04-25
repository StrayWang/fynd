<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszNutritionCommendModel extends Fynd_Model
{
    private $_nutritionCommendId;
    private $_humanCategoryId;
    private $_nutritionId;
    private $_nutritionValue;
    
    /**
     * @return number
     */
    public function getNutritionCommendId()
    {
        return $this->_nutritionCommendId;
    }
    /**
     * @param number
     */
    public function setNutritionCommendId($_nutritionCommendId)
    {
        $this->_nutritionCommendId = $_nutritionCommendId;
    }
    /**
     * @return number
     */
    public function getHumanCategoryId()
    {
        return $this->_humanCategoryId;
    }
    /**
     * @param number
     */
    public function setHumanCategoryId($_humanCategoryId)
    {
        $this->_humanCategoryId = $_humanCategoryId;
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