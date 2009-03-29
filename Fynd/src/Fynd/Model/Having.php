<?php
require_once ('Fynd/List.php');
require_once 'Fynd/Db/ISQLBuilder.php';
class Fynd_Model_Having extends Fynd_List implements Fynd_Db_ISQLBuilder  
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
            foreach ($this->_items as $item)
            {
                $this->_sql .= $item->createSQL();
            }
        }
        return $this->_sql;
    }

}
?>