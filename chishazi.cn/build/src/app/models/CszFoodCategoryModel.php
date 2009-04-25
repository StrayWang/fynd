<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszFoodCategoryModel extends Fynd_Model
{
    private $_foodCategoryId;
    private $_categoryName;
    
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