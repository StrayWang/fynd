<?php
require_once ('Fynd/Dictionary.php');
require_once 'Fynd/Db/ISQLBuilder.php';
/**
 * This class describe the "group by" clause in sql.
 * It inherit from Fynd_Dictionary,so,the key of dictionary as "group by" clause's expression,
 * the value of dictionary as grouping order direction,usually the value is ASC or DESC,
 * default value is ASC.  
 *
 */
class Fynd_Model_Group extends Fynd_Dictionary implements Fynd_Db_ISQLBuilder 
{
    /**
     * The having clause
     *
     * @var Fynd_Model_Having
     */
    private $_having;
    
    private $_sql;
    
    /**
     * @return Fynd_Model_Having
     */
    public function getHaving()
    {
        return $this->_having;
    }
    
    /**
     * @param Fynd_Model_Having $_having
     */
    public function setHaving($_having)
    {
        $this->_having = $_having;
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
            $this->_sql = "GROUP BY ";
            foreach ($this->_items as $filed)
            {
                $this->_sql .= $filed->createSQL();
            }
            $this->_sql .= $this->_having->createSQL();
        }
        return $this->_sql;
    }

    
}
?>