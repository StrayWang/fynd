<?php
require_once ('Fynd/Db/ISQLBuilder.php');
require_once ('Fynd/Object.php');
class Fynd_Model_Insertion extends Fynd_Object implements Fynd_Db_ISQLBuilder 
{
    /**
     * The model should be inserted into database.
     *
     * @var Fynd_Model
     */
    private $_model;
    
    private $_sql;
    
    public function __construct(Fynd_Model $m)
    {
        $this->_model = $m;
    }
    /**
     * Get parameterized sql string.
     * @see Fynd_Db_ISQLBuilder::CreateSQL()
     * @return string
     */
    public function createSQL()
    {
        $pv = $this->_model->getPrimaryPropertyValue();
        if(! empty($pv))
        {
            Fynd_Object::throwException("Fynd_Model_Exception","This model is not for insertion.The primary key must be empty.");
        }
        if(empty($this->_sql))
        {
            $this->_sql = "INSERT INTO ";
            $this->_sql .= $this->_model->getMeta()->getTableName() . " (";
            $fieldsPart = "";
            $valuesPart = "";
            $entities = $this->_model->getEntites();
            foreach($entities as $entity)
            {
                $fieldsPart .= "`" . $entity->getField() . "`,";
                $valuesPart .= ":p_" . $entity->getField() . ",";
            }
            if(Fynd_StringUtil::endWith($fieldsPart, ","))
            {
                $fieldsPart = Fynd_StringUtil::removeEnd($fieldsPart, 1);
            }
            if(Fynd_StringUtil::endWith($valuesPart, ","))
            {
                $valuesPart = Fynd_StringUtil::removeEnd($valuesPart, 1);
            }
            $this->_sql .= $fieldsPart . ") VALUES (" . $valuesPart . ")";
        }
        return $this->_sql;
    }
    
}
?>