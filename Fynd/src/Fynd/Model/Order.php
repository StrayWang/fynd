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
     * @var Fynd_Model
     */
    protected $_model;
    
    public function __construct(Fynd_Model $model,array $array = null)
    {
        if(!is_null($array))
        {
            parent::__construct($array);
        }
        $this->_model = $model;
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
            $this->_sql = " ORDER BY ";
            foreach($this->_items as $orderby => $dir)
            {
                $field = '';
                if(!is_null($this->_model))
                {
                    $entities = $this->_model->getEntites();
                    $field = $entities[$orderby]->getField();
                }
                else
                {
                    $field = $orderby;
                }
                $this->_sql .= $field . ' ' . $dir . ',';
            }
            if(Fynd_StringUtil::endWith($this->_sql,','))
            {
                $this->_sql = Fynd_StringUtil::removeEnd($this->_sql);
            }
        }
        return $this->_sql;
    }
}
?>