<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszHumanModel extends Fynd_Model
{
    private $_humanId;
    private $_humanCategoryId;
    private $_eatingHabitId;
    private $_userId;
    private $_weight;
    private $_height;
    private $_birthYear;
    private $_birthMonth;
    private $_isSmoking;
    private $_isDrinking;
    
    /**
     * @return number
     */
    public function getHumanId()
    {
        return $this->_humanId;
    }
    /**
     * @param number
     */
    public function setHumanId($_humanId)
    {
        $this->_humanId = $_humanId;
    }
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
     * @return number
     */
    public function getEatingHabitId()
    {
        return $this->_eatingHabitId;
    }
    /**
     * @param number
     */
    public function setEatingHabitId($_eatingHabitId)
    {
        $this->_eatingHabitId = $_eatingHabitId;
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
     * @return number
     */
    public function getWeight()
    {
        return $this->_weight;
    }
    /**
     * @param number
     */
    public function setWeight($_weight)
    {
        $this->_weight = $_weight;
    }
    /**
     * @return number
     */
    public function getHeight()
    {
        return $this->_height;
    }
    /**
     * @param number
     */
    public function setHeight($_height)
    {
        $this->_height = $_height;
    }
    /**
     * @return string
     */
    public function getBirthYear()
    {
        return $this->_birthYear;
    }
    /**
     * @param string
     */
    public function setBirthYear($_birthYear)
    {
        $this->_birthYear = $_birthYear;
    }
    /**
     * @return string
     */
    public function getBirthMonth()
    {
        return $this->_birthMonth;
    }
    /**
     * @param string
     */
    public function setBirthMonth($_birthMonth)
    {
        $this->_birthMonth = $_birthMonth;
    }
    /**
     * @return string
     */
    public function getIsSmoking()
    {
        return $this->_isSmoking;
    }
    /**
     * @param string
     */
    public function setIsSmoking($_isSmoking)
    {
        $this->_isSmoking = $_isSmoking;
    }
    /**
     * @return string
     */
    public function getIsDrinking()
    {
        return $this->_isDrinking;
    }
    /**
     * @param string
     */
    public function setIsDrinking($_isDrinking)
    {
        $this->_isDrinking = $_isDrinking;
    }
}
?>