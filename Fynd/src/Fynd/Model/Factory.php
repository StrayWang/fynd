<?php
require_once 'Fynd/Object.php';
require_once 'Fynd/Db/Factory.php';
require_once 'Fynd/Model/State.php';
/**
 * This is model factory class,
 * it provide some method to create model object or model object collection.
 * @author fishtrees
 * @version 20090308
 *
 */
final class Fynd_Model_Factory extends Fynd_Object
{
    /**
     * @var Fynd_Log
     */
    private static $_log = null;
    private function __construct()
    {}
    /**
     * @return Fynd_Log
     */
    private static function _getLogger()
    {
        if(is_null(self::$_log))
        {
            self::$_log = Fynd_Application::getLogger('Fynd_Model_Factory');
        }
        return self::$_log;
    }
    /**
     * Create a new instance of giving model type.
     *
     * @param Fynd_Type $mt
     * @param Array|Object $values 
     */
    public static function createModel(Fynd_Type $mt, $values = null)
    {
        /**
         * @var Fynd_Model
         */
        $model = $mt->createInstance();
        $ref = $mt->getReflection();
        $filename = str_replace('.php', '.xml', $ref->getFileName());
        //hu lue
        $xml = @simplexml_load_file($filename);
        $primaryProperty = (string)$xml['PrimaryProperty'];
        $meta = new Fynd_Model_Meta();
        $meta->setTableName((string)$xml['Table']);
        $meta->setState(Fynd_Model_State::NONE);
        $meta->setPrimaryProperty($primaryProperty);
        $model->setMeta($meta);
        if(! is_null($xml) && false !== $xml)
        {
            foreach($xml as $node)
            {
                $entity = new Fynd_Model_Entity();
                $entity->setProperty((string)$node->Property);
                $entity->setField((string)$node->Field);
                $dataTypeName = (string)$node->DataType;
                $entity->setDataType(Fynd_Db_DataType::getDataTypeEnum($dataTypeName));
                $model->addEntity($entity);
                $field = $entity->getField();
                if(! is_null($values))
                {
                    $property = $entity->getProperty();
                    if(is_array($values) && array_key_exists($field, $values))
                    {
                        $model->setPropertyValue($property, $values[$field]);
                    }
                    else if(is_object($values))
                    {
                        $model->setPropertyValue($property, $values->$property);
                    }
                    else
                    {
                        self::_getLogger()->logWarn('property is not exist in $values');
                    }
                }
            }
        }
        return $model;
    }
    /**
     * Gets the count of model which matches the filter.
     *
     * @param Fynd_Type $mt
     * @param string $filter
     * @return number
     */
    public static function getModelCount(Fynd_Type $mt,$filter)
    {
        $isOpen = false;
        try
        {
            $db = Fynd_Db_Factory::getConnection();
            $tplModel = self::createModel($mt);
            $sel = new Fynd_Model_Selection();
            $sel->setFromModel($tplModel);
            $selExpr = new Fynd_Model_SelectExpr();
            $selExpr->setExpression('count(*) AS countNum');
            $sel->addSelectExpression($selExpr);
            $parameterized = Fynd_Db_Util::toParameterizedSQL($filter);
            if(! empty($parameterized['sql']))
            {
                $where = new Fynd_Model_Where($tplModel);
                $filter = new Fynd_Model_Filter();
                $filter->setExpression($parameterized['sql']);
                $where->addFilter($filter);
                $sel->setWhereClause($where);
            }
            
            $sql = $sel->createSQL();
            self::_getLogger()->logInfo($sql);
            
            $cmd = $db->createCommand($sql);
            foreach($parameterized['parameters'] as $param)
            {
                if($param)
                {
                    $cmd->addParameter($param);
                }
            }
            $isOpen = $db->open();
            $result = intval($cmd->executeScalar());
            if($isOpen)
            {
                $db->close();
            }
        }
        catch(Exception $e)
        {
            if($isOpen)
            {
                $db->Close();
            }
            throw $e;
        }
        return $result;
    }
    /**
     * Search database, get matching records, create models, and return a model list. 
     *
     * @param Fynd_Type $mt
     * @param string $filterString the filterString will follow the "WHERE" keyword
     * @param int $pageSize use -1 to get the all models
     * @param int $startOffset
     * @param string $orderby
     * @param string $orderDirection
     * @return Fynd_Model_List
     */
    public static function getModels(Fynd_Type $mt, $filterString = "", $pageSize = 20, $startOffset = 0 ,$orderby='',$orderDirection = 'asc')
    {
        $isOpen = false;
        $models = new Fynd_Model_List();
        try
        {
            $db = Fynd_Db_Factory::getConnection();
            $tplModel = self::createModel($mt);
            $sel = new Fynd_Model_Selection();
            $sel->setFromModel($tplModel);
            $parameterized = Fynd_Db_Util::toParameterizedSQL($filterString);
            if(! empty($parameterized['sql']))
            {
                $where = new Fynd_Model_Where($tplModel);
                $filter = new Fynd_Model_Filter();
                $filter->setExpression($parameterized['sql']);
                $where->addFilter($filter);
                $sel->setWhereClause($where);
            }
            if(!empty($orderby))
            {
                $order = new Fynd_Model_Order($tplModel);
                $order->add($orderby,$orderDirection);
                $sel->setOrderByClause($order);
            }
            $sql = $sel->createSQL();
            self::_getLogger()->logInfo($sql);
            if($pageSize != - 1)
            {
                $paging = new Fynd_Db_Paging();
                $paging->setConnectionObjectType($db->getType());
                $paging->setOrginSQL($sql);
                $paging->setStartOffset($startOffset);
                $paging->setPageSize($pageSize);
                $sql = $paging->createSQL();
            }
            $cmd = $db->createCommand($sql);
            foreach($parameterized['parameters'] as $param)
            {
                if($param)
                {
                    $cmd->addParameter($param);
                }
            }
            $isOpen = $db->open();
            $result = $cmd->execute();
            foreach($result as $row)
            {
                $model = self::createModel($mt, $row);
                $models->add($model);
            }
            if($isOpen)
            {
                $db->close();
            }
        }
        catch(Exception $e)
        {
            if($isOpen)
            {
                $db->Close();
            }
            throw $e;
            //            Fynd_Object::throwException("Fynd_Model_Exception",
        //            		"Exception thrown when getting models from database:" . $e->getMessage(),
        //                    $e->getCode());
        }
        return $models;
    }
    /**
     * Get a single model which match the filters from the database,
     * if more than one models have been found,it will return the first one.
     *
     * @param Fynd_Type $mt
     * @param string $filterString
     * @param Fynd_List $dbParams
     * @param Fynd_List $filters
     * 
     * @return Fynd_Model
     */
    public static function getModel(Fynd_Type $mt, $filterString = "", Fynd_List $dbParams = null)
    {
        $isOpen = false;
        $model = null;
        try
        {
            $db = Fynd_Db_Factory::getConnection();
            $tplModel = self::createModel($mt);
            $sel = new Fynd_Model_Selection();
            $sel->setFromModel($tplModel);
            if(! empty($filterString))
            {
                $where = new Fynd_Model_Where($tplModel);
                $filter = new Fynd_Model_Filter();
                $filter->setExpression($filterString);
                $where->addFilter($filter);
                $sel->setWhereClause($where);
            }
            $sql = $sel->createSQL();
            $paging = new Fynd_Db_Paging();
            $paging->setConnectionObjectType($db->getType());
            $paging->setOrginSQL($sql);
            $paging->setPageNo(1);
            $paging->setPageSize(1);
            $sql = $paging->CreateSQL();
            $cmd = $db->createCommand($sql);
            if(! is_null($dbParams))
            {
                foreach($dbParams as $param)
                {
                    $cmd->addParameter($param);
                }
            }
            $isOpen = $db->open();
            $result = $cmd->execute();
            if(count($result) > 0)
            {
                $model = self::createModel($mt, $result[0]);
            }
            if($isOpen)
            {
                $db->close();
            }
        }
        catch(Exception $e)
        {
            if($isOpen)
            {
                $db->close();
            }
            Fynd_Object::ThrowException("Fynd_Model_Exception", "Exception thrown when getting single model from database:" . $e->getMessage(), $e->getCode());
        }
        return $model;
    }
}
?>