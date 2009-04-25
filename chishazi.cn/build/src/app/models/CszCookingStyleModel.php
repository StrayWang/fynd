<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszCookingStyleModel extends Fynd_Model
{
    private $_cookingStyleId;
    
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
}
?>