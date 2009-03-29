<?php
require_once ('Fynd/Object.php');
require_once 'Fynd/Db/ISQLBuilder.php';
class Fynd_Model_SelectExpr extends Fynd_Object implements Fynd_Db_ISQLBuilder 
{
    const SQL_FUN_MAX = "MAX";
    const SQL_FUN_COUNT = "COUNT";
    const SQL_FUN_SUM = "SUM";
    
    private $_sql;
    /**
     * The owner of selection field,mybe a table name or a table alias.
     *
     * @var string
     */
    private $_owner;
    /**
     * The entity of a model
     *
     * @var Fynd_Model_Entity
     */
    private $_entity;
    /**
     * The selection field's alias
     *
     * @var string
     */
    private $_alias;
    /**
     * The database function which will applied to the selection field.
     *
     * @var string
     */
    private $_function;
    /**
     * The selection field expression,
     * if don't set its value directly,it will be evaluated by model entity, database function 
     * and the owner.
     *
     * @var string
     */
    private $_expression;
    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->_owner;
    }
    /**
     * @param string $_owner
     */
    public function setOwner($_owner)
    {
        $this->_owner = $_owner;
    }
    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }
    /**
     * @return Fynd_Model_Entity
     */
    public function getEntity()
    {
        return $this->_entity;
    }
    /**
     * @return string
     */
    public function getExpression()
    {
        if(empty($this->_expression))
        {
            $this->_expression = $this->createSQL();
        }
        return $this->_expression;
    }
    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->_function;
    }
    /**
     * @param string $_alias
     */
    public function setAlias($_alias)
    {
        $this->_alias = $_alias;
    }
    /**
     * @param Fynd_Model_Entity $_entity
     */
    public function setEntity(Fynd_Model_Entity $_entity)
    {
        $this->_entity = $_entity;
    }
    /**
     * @param string $_expression
     */
    public function setExpression($_expression)
    {
        $this->_expression = $_expression;
    }
    /**
     * @param string $_function
     */
    public function setFunction($_function)
    {
        $this->_function = $_function;
    }
    /**
     * @see Fynd_Db_ISQLBuilder::CreateSQL()
     *
     * @return string
     */
    public function createSQL()
    {
        if(empty($this->_sql))
        {
            if(!empty($this->_owner))
            {
                $this->_sql .= $this->_owner . ".";
            }
            if(!empty($this->_function))
            {
                $this->_sql .= $this->_function . "(" . $this->_entity->getField() . ") ";
            }
            if(!empty($this->_alias))
            {
                $this->_sql .= " AS " . $this->_alias;
            }
        }
        return $this->_sql;
    }
    
}
?>