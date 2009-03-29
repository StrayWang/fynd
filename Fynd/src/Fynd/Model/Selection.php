<?php
require_once 'Fynd/Object.php';
require_once 'Fynd/Db/ISQLBuilder.php';
class Fynd_Model_Selection extends Fynd_Object implements Fynd_Db_ISQLBuilder
{
    /**
     * The joins in this model selection. 
     *
     * @var array
     */
    private $_joins = array();
    /**
     * The where clause in this model selection.
     *
     * @var Fynd_Model_Where
     */
    private $_where;
    /**
     * Order by clause
     *
     * @var Fynd_Model_Order
     */
    private $_orderby;
    /**
     * The "from" model
     *
     * @var fynd_Model
     */
    private $_from;
    /**
     * The fields which will be selected out from the "from" model and its joins.
     * The array elements's type is Fynd_Model_SelectionField
     *
     * @var array
     */
    private $_fields = array();
    /**
     * The selection's alias as a subquery.
     *
     * @var string
     */
    private $_alias;
    /**
     * Determine that if the result set of this selection is distinct
     *
     * @var bool
     */
    private $_isDistinct;
    /**
     * The group by clause
     *
     * @var Fynd_Model_Group
     */
    private $_group;
    
    /**
     * @param string $_alias
     */
    public function setSelectionAlias($alias)
    {
        $this->_alias = $alias;
    }
    /**
     * @param fynd_Model $_from
     */
    public function setFromModel(Fynd_Model $model)
    {
        $this->_from = $model;
    }
    /**
     * @param Fynd_Model_Group $_group
     */
    public function setGroupByClause(Fynd_Model_Group $group)
    {
        $this->_group = $group;
    }
    /**
     * @param bool $_isDistinct
     */
    public function setIsDistinct($isDistinct)
    {
        $this->_isDistinct = $isDistinct;
    }
    /**
     * @param Fynd_Model_Order $_orderby
     */
    public function setOrderByClause(Fynd_Model_Order $orderby)
    {
        $this->_orderby = $orderby;
    }
    /**
     * @param Fynd_Model_Where $_where
     */
    public function setWhereClause(Fynd_Model_Where $where)
    {
        $this->_where = $where;
    }

    
    /**
     * Add a new select-expr to the selection object
     *
     * @param Fynd_Model_SelectExpr $expr
     */
    public function addSelectExpression(Fynd_Model_SelectExpr $expr)
    {
        if(!in_array($expr,$this->_fields))
        {
            array_push(&$this->_fields,$expr);
        }
    }
    /**
     * Join two models together,with their PK or FK.
     *
     * @param Fynd_Model $slave Slave model
     * @param Fynd_Model $primary Primary model,if it is null,will join slave with "from" model.
     */
    public function join(Fynd_Model $slave, Fynd_Model $primary = null)
    {
        $join = new Fynd_Model_Join();
        $join->setSlave($slave);
        if(null === $primary)
        {
            $join->setPrimary($this->_from);
        }
        else
        {
            $join->setPrimary($primary);
        }
        $join->setSlaveKey($join->getSlave()->getMeta()->getPrimaryProperty());
        $join->setPrimaryKey($join->getPrimary()->getMeta()->getPrimaryProperty());
    }
    /**
     * Build SQL which described by this selection object.
     *  
     * @see Fynd_Db_ISQLBuilder::createSQL()
     * @return string
     */
    public function createSQL()
    {
        if(empty($this->_sql))
        {
            //SELECT
            $this->_sql = "SELECT ";
            //DISTINCT
            if($this->_isDistinct === true)
            {
                $this->_sql .= "DISTINCT ";
            }
            //SELECT-EXPR
            $tableAliases = array();
            $tableAliasChar = 96;
            $fromTableAlias = $this->_getTableAlias($this->_from, $tableAliases, $tableAliasChar);
            $sqlFrom = "From " . $this->_from->GetMeta()->getTableName() . " AS " . $fromTableAlias;
            foreach($this->_fields as $field)
            {
                $field->SetOwner($this->_getTableAlias($field->getEntity()->getModel(), $tableAliases, $tableAliasChar));
                $this->_sql .= $field->createSQL() . ",";
            }
            //FROM
            $this->_sql .= $sqlFrom;
            //JOIN
            foreach ($this->_joins as $join)
            {
                $this->_sql .= $join->createSQL();
            }
            //WHERE
            foreach ($this->_wheres as $where)
            {
                $this->_sql .= $where->createSQL();
            }
            //GROUP BY
            $this->_sql .= $this->_group->createSQL();
            //ORDER BY
            $this->_sql .= $this->_orderby->createSQL();
        }
        return $this->_sql;
    }
    private function _getTableAlias(Fynd_Model $model, &$aliases, &$currentChar)
    {
        $key = $model->getHashCode();
        if(array_key_exists($key, $aliases))
        {
            return $aliases[$key];
        }
        $currentChar ++;
        $char = chr($currentChar);
        $aliases[$key] = $char;
        return $char;
    }
}
?>