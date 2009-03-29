<?php
require_once ('Fynd/Db/ISQLBuilder.php');
require_once ('Fynd/Object.php');
class Fynd_Model_Delete extends Fynd_Object implements Fynd_Db_ISQLBuilder
{
    /**
     * @var Fynd_Model
     */
    private $_model;
    /**
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
     * 
     * @return string 
     * @see Fynd_Db_ISQLBuilder::createSQL()
     */
    public function createSQL()
    {
        if(empty($this->_sql))
        {
            $this->_sql = "DELETE FROM ";
            $this->_sql .= $this->_model->getMeta()->getTableName() . " ";
            $this->_sql .= $this->_where->createSQL();    
        }
        return $this->_sql;
    }
}
?>