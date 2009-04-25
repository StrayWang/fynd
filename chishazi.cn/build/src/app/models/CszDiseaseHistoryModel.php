<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszDiseaseHistoryModel extends Fynd_Model
{
    private $_diseaseHistoryId;
    private $_diseaseId;
    private $_humanId;
    private $_fromTime;
    private $_severity;
    
    /**
     * @return number
     */
    public function getDiseaseHistoryId()
    {
        return $this->_diseaseHistoryId;
    }
    /**
     * @param number
     */
    public function setDiseaseHistoryId($_diseaseHistoryId)
    {
        $this->_diseaseHistoryId = $_diseaseHistoryId;
    }
    /**
     * @return number
     */
    public function getDiseaseId()
    {
        return $this->_diseaseId;
    }
    /**
     * @param number
     */
    public function setDiseaseId($_diseaseId)
    {
        $this->_diseaseId = $_diseaseId;
    }
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
     * @return string
     */
    public function getFromTime()
    {
        return $this->_fromTime;
    }
    /**
     * @param string
     */
    public function setFromTime($_fromTime)
    {
        $this->_fromTime = $_fromTime;
    }
    /**
     * @return string
     */
    public function getSeverity()
    {
        return $this->_severity;
    }
    /**
     * @param string
     */
    public function setSeverity($_severity)
    {
        $this->_severity = $_severity;
    }
}
?>