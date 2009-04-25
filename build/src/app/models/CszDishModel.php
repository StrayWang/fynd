<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszDishModel extends Fynd_Model
{
    private $_dishId;
    private $_cookingStyleId;
    private $_cookingModeId;
    
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
    /**
     * @return number
     */
    public function getCookingStyleId()
    {
        return $this->_cookingStyleId;
    }
    /**
     * @param number
     */
    public function setCookingStyleId($_cookingStyleId)
    {
        $this->_cookingStyleId = $_cookingStyleId;
    }
    /**
     * @return number
     */
    public function getCookingModeId()
    {
        return $this->_cookingModeId;
    }
    /**
     * @param number
     */
    public function setCookingModeId($_cookingModeId)
    {
        $this->_cookingModeId = $_cookingModeId;
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