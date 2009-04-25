<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszPassportModel extends Fynd_Model
{
    private $_passportId;
    private $_userId;
    private $_passport;
    private $_password;
    
    /**
     * @return number
     */
    public function getPassportId()
    {
        return $this->_passportId;
    }
    /**
     * @param number
     */
    public function setPassportId($_passportId)
    {
        $this->_passportId = $_passportId;
    }
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
    /**
     * @return string
     */
    public function getPassport()
    {
        return $this->_passport;
    }
    /**
     * @param string
     */
    public function setPassport($_passport)
    {
        $this->_passport = $_passport;
    }
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }
    /**
     * @param string
     */
    public function setPassword($_password)
    {
        $this->_password = $_password;
    }
}
?>