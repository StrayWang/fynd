<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszUserModel extends Fynd_Model
{
    private $_userId;
    
    /**
     * @return number
     */
    public function getUserId()
    {
        return $this->_userId;
    }
    /**
     * @param number
     */
    public function setUserId($_userId)
    {
        $this->_userId = $_userId;
    }
}
?>