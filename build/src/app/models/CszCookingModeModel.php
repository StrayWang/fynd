<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszCookingModeModel extends Fynd_Model
{
    private $_cookingModeId;
    
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
}
?>