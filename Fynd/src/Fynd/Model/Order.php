<?php
require_once ('Fynd/Db/ISQLBuilder.php');
require_once ('Fynd/Dictionary.php');
/**
 * This class describe the order clause in sql.
 * It inherit from Fynd_Dictionary,so,the key of dictionary as order clause's expression,
 * the value of dictionary as order direction,usually the value is ASC or DESC,
 * default value is ASC.  
 *
 */
class Fynd_Model_Order extends Fynd_Dictionary implements Fynd_Db_ISQLBuilder
{
    private $_sql;
    /**
     * @see Fynd_Db_ISQLBuilder::CreateSQL()
     *
     * @return string
     */
    public function createSQL()
    {    
        if(empty($this->_sql))
        {
            foreach($this->_items as $item)
            {
                $$this->_sql .= $item->createSQL();
            }
        }
        return $$this->_sql;
    }
}
?>