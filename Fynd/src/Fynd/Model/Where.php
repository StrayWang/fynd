<?php
require_once ('Fynd/Db/ISQLBuilder.php');
require_once ('Fynd/Object.php');
class Fynd_Model_Where extends Fynd_Object implements Fynd_Db_ISQLBuilder
{
    /**
     * @var Fynd_List
     */
    private $_filters;
    /**
     * @var Fynd_Model
     */
    private $_model;
    private $_sql;
    /**
     * @param Fynd_Model $model
     * @param Fynd_List $filters
     */
    public function __construct(Fynd_Model $model, Fynd_List $filters = null)
    {
        $this->_model = $model;
        if(null != $model && null == $filters)
        {
            $primaryProperty = $this->_model->getMeta()->getPrimaryProperty();
            $entities = $this->_model->getEntites();
            $entity = $entities[$primaryProperty];
            
            $filter = new Fynd_Model_Filter($entity);
            $this->_filters->add($filter);
        }
    }
    /**
     * Add a Fynd_Model_Filter to where clause
     *
     * @param Fynd_Model_Filter $filter
     */
    public function addFilter(Fynd_Model_Filter $filter)
    {
        $this->_filters->add($filter);
    }
    public function createSQL()
    {
        if(empty($this->_sql))
        {
            $this->_sql = "WHERE ";
            foreach($this->_filters as $filter)
            {
                $this->_sql .= $filter->createSQL();
            }
        }
        return $this->_sql;
    }
}
?>