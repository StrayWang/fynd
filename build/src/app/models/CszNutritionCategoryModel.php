<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszNutritionCategoryModel extends Fynd_Model
{
    private $_nutritionCategoryId;
    private $_parentCategoryId;
    private $_categoryName;
    
    /**
     * @return number
     */
    public function getNutritionCategoryId()
    {
        return $this->_nutritionCategoryId;
    }
    /**
     * @param number
     */
    public function setNutritionCategoryId($_nutritionCategoryId)
    {
        $this->_nutritionCategoryId = $_nutritionCategoryId;
    }
    /**
     * @return number
     */
    public function getParentCategoryId()
    {
        return $this->_parentCategoryId;
    }
    /**
     * @param number
     */
    public function setParentCategoryId($_parentCategoryId)
    {
        $this->_parentCategoryId = $_parentCategoryId;
    }
    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->_categoryName;
    }
    /**
     * @param string
     */
    public function setCategoryName($_categoryName)
    {
        $this->_categoryName = $_categoryName;
    }
}
?>