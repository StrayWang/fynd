<?php
require_once 'Fynd/Model.php';
/**
 * Created by Fynd model creation tool.
 */
class CszDiseaseModel extends Fynd_Model
{
    private $_diseaseId;
    private $_diseaseName;
    
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
     * @return string
     */
    public function getDiseaseName()
    {
        return $this->_diseaseName;
    }
    /**
     * @param string
     */
    public function setDiseaseName($_diseaseName)
    {
        $this->_diseaseName = $_diseaseName;
    }
}
?>