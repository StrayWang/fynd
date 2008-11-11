<?php
include_once ('Model.php');
require_once 'Model/ModelSelection.php';
class User extends Fynd_Model
{
    protected $_userId;
    protected $_firstName;
    protected $_lastName;
    protected $_passport;
    protected $_password;
    protected $_created;
    protected $_crteatedby;
    protected $_modified;
    protected $_modifiedby;
    protected $_lastLoginTime;
    protected $_lastLoginIp;
    protected $_loginCount;
    protected $_email;
    /**
     * @param string $passport
     */
    public function setPassport ($passport)
    {
        $this->_passport = $passport;
    }
    /**
     * @param string $password
     */
    public function setPassword ($password)
    {
        $this->_password = $password;
    }
    public function setEmail ($email)
    {
        if (preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email) == 0)
        {
            throw new Exception('Email格式不正确');
        }
        $sel = new Fynd_Model_ModelSelection();
        $sel->Property = 'Email';
        $sel->ConditionValue = $email;
        if ($this->select($sel, true) > 0)
        {
            throw new Exception('Email已经被使用了');
        }
        $this->_email = $email;
    }
}
?>