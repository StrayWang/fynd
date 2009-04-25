<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszNutritionModel extends Fynd_Model
{
    private $_nutritionId;
    private $_nutritionCategoryId;
    private $_nutritionName;
    
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
     * @return string
     */
    public function getNutritionName()
    {
        return $this->_nutritionName;
    }
    /**
     * @param string
     */
    public function setNutritionName($_nutritionName)
    {
        $this->_nutritionName = $_nutritionName;
    }
    /**
     * @see Serializable::serialize()
     *
     */
    public function serialize()
    {}
    /**
     * @see Serializable::unserialize()
     *
     * @param serialized $serialized
     */
    public function unserialize($serialized)
    {}
}
?>