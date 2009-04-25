<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszHumanCategoryModel extends Fynd_Model
{
    private $_humanCategoryId;
    private $_categoryName;
    
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