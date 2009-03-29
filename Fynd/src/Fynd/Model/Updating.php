<?php
require_once ('Fynd/Db/ISQLBuilder.php');
require_once ('Fynd/Object.php');
class Fynd_Model_Updating extends Fynd_Object implements Fynd_Db_ISQLBuilder
{
    /**
     * The model object for updating
     *
     * @var Fynd_Model
     */
    private $_model;
    /**
     * The where clause
     *
     * @var Fynd_Model_Where
     */
    private $_where;
    private $_sql;
    public function __construct(Fynd_Model $model, Fynd_Model_Where $where = null)
    {
        $this->_model = $model;
        if(null == $where)
        {
            $where = new Fynd_Model_Where($model);
        }
        $this->_where = $where;
    }
    /**
     * @param bool $parameterized using parameterized sql or not
     */
    private function _getSQL()
    {
        if(empty($this->_sql))
        {
            $this->_sql = "UPDATE ";
            $this->_sql .= $this->_model->getMeta()->getTableName() . " SET ";
            $entities = $this->_model->getEntites();
            $modifiedCount = 0;
            foreach($entities as $property => $entity)
            {
                //ignore the non-modified fields and primary key
                if($entity->getState() != Fynd_Model_State::Modified || $property == $this->_model->getMeta()->getPrimaryProperty())
                {
                    continue;
                }
                $this->_sql .= "`" . $entity->getField() . "` = ";
                $this->_sql .= ":p_" . $entity->getField() . ",";
                $modifiedCount ++;
            }
            if($modifiedCount > 0)
            {
                $this->_sql = Fynd_StringUtil::removeEnd($this->_sql);
                $this->_sql .= " " . $this->_where->createSQL();
            }
            else
            {
                $this->_sql = "";
            }
        }
        return $this->_sql;
    }
    /**
     * 
     * @return string 
     * @see Fynd_Db_ISQLBuilder::createSQL()
     */
    public function createSQL()
    {
        return $this->_getSQL();
    }
}
?>