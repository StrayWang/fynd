<?php
require_once ('Fynd/Object.php');
require_once 'Fynd/Db/ISQLBuilder.php';
class Fynd_Model_Join extends Fynd_Object implements Fynd_Db_ISQLBuilder
{
    private $_sql;
    /**
     * The slaved model in join operation
     *
     * @var Fynd_Model
     */
    private $_slave;
    /**
     * The primary model in join operation
     *
     * @var Fynd_Model
     */
    private $_primary;
    /**
     * The selection expression used to join primary model in slaved model.
     *
     * @var Fynd_Model_Entity
     */
    private $_slaveKey;
    /**
     * The selection expression used to join slaved model in primary model.
     *
     * @var Fynd_Model_Entity
     */
    private $_primaryKey;
    /**
     * The join type,use Fynd_Model_Join's const to evaluate it. 
     *
     * @var string
     */
    private $_joinType;
    
    
    const LEFT = "LEFT JOIN ";
    const RIGHT = "RIGHT JOIN ";
    const INNER = "INNER JOIN ";
    
    /**
     * @return Fynd_Model
     */
    public function getPrimary()
    {
        return $this->_primary;
    }
    /**
     * @return Fynd_Model_Entity
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }
    /**
     * @return Fynd_Model
     */
    public function getSlave()
    {
        return $this->_slave;
    }
    /**
     * @return Fynd_Model_Entity
     */
    public function getSlaveKey()
    {
        return $this->_slaveKey;
    }
    /**
     * @param Fynd_Model $_primary
     */
    public function setPrimary(Fynd_Model $_primary)
    {
        $this->_primary = $_primary;
    }
    /**
     * @param Fynd_Model_Entity $_primaryKey
     */
    public function setPrimaryKey(Fynd_Model_Entity $_primaryKey)
    {
        $this->_primaryKey = $_primaryKey;
    }
    /**
     * @param Fynd_Model $_slave
     */
    public function setSlave(Fynd_Model $_slave)
    {
        $this->_slave = $_slave;
    }
    
    /**
     * @param Fynd_Model_Entity $_slaveKey
     */
    public function setSlaveKey(Fynd_Model_Entity $_slaveKey)
    {
        $this->_slaveKey = $_slaveKey;
    }
    /**
     * @see Fynd_Db_ISQLBuilder::CreateSQL()
     *
     */
    public function createSQL()
    {
        if(empty($this->_sql))
        {
            $this->_sql = Fynd_Model_Filter::LB . "";
            $this->_sql .= $this->_joinType . " ";
            $this->_sql .= $this->_slave->getMeta()->getTableName();
            
            $slaveAlias = $this->_slave->getMeta()->getAlias();
            if(!empty($slaveAlias))
            {
                $this->_sql .= " AS " . $slaveAlias;
                $this->_sql .= "ON " . $slaveAlias . ".";
            }
            else
            {
                $this->_sql .= "ON ";
            }
            $this->_sql .=  $this->_slaveKey->getField();
            
            $this->_sql .= " = ";
            
            $primaryAlias = $this->_primary->getMeta()->getTableName();
            if(!empty($primaryAlias))
            {
                $sql .= $primaryAlias . ".";
            }
            $this->_sql .=  $this->_primaryKey->getField();
            
            $this->_sql .= Fynd_Model_Filter::RB . " ";
        }
        return $sql;
        
    }
}
?>