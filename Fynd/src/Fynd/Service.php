<?php
require_once ('Fynd/Object.php');
abstract class Fynd_Service extends Fynd_Object
{
    /**
     * @var Fynd_User
     */
    private $_user;
    /**
     * @var      Fynd_Db_IConnection
     */
    private $_defaultDbConn;
    /**
     * @var      Fynd_Db_IConnection
     */
    private $_userDbConn;
    /**
     * Set the user's database connection object
     *
     * @param Fynd_Db_IConnection $conn
     */
    public function setUserDbConnection(Fynd_Db_IConnection $conn)
    {
        $this->_userDbConn = $conn;
    }
    /**
     * Get the user's database connection object
     *
     * @return Fynd_Db_IConnection
     */
    public function getUserDbConnection()
    {
        return $this->_userDbConn;
    }
    /**
     * Set the default database connection object
     *
     * @param Fynd_Db_IConnection $conn
     */
    public function setDefaultDbConnection(Fynd_Db_IConnection $conn)
    {
        $this->_defaultDbConn = $conn;
    }
    /**
     * Get the default database connection object
     *
     * @return Fynd_Db_IConnection
     */
    public function getDefaultDbConnection()
    {
        return $this->_defaultDbConn;
    }
    /**
     * Set the user object in this service
     *
     * @param Fynd_User $user
     */
    public function setUser(Fynd_User $user)
    {
        $this->_user = $user;
    }
    /**
     * Get the user object in this service
     *
     * @return Fynd_User
     */
    public function getUser()
    {
        return $this->_user;
    }
    /**
     * Save the model,if the model is fresh,generate a new value of primary key.
     * 
     * @param    Fynd_Model $model    
     * @param 	 Fynd_Db_IConnection $conn
     * @return   void
     */
    public function save(Fynd_Model $model, Fynd_Db_IConnection $conn = null)
    {
        $conn = ($conn == null) ? $this->userDbConn : $conn;
        
        $primaryProperty = $model->getMeta()->getPrimaryProperty();
        $primaryValue = $model->getPropertyValue($primaryProperty);
        $entites = $model->getEntites();
        $sql = "";
        $cmd = $conn->createCommand();
        $params = new Fynd_List();
        if(empty($primaryValue))
        {
            $primaryValue = $entites[$primaryKey]->getSeq->getNextValue();
            $sql = $model->createInsertSQL($primaryValue,$params);
        }
        else
        {
            $sql = $model->createUpdateSQL($params);
        }
        $cmd->setCommandText($sql);
        foreach($params as $param)
        {
            $cmd->addParameter($param);
        }
        $isConnOpen = false;
        try
        {
            $isConnOpen = $conn->open();
            $cmd->excuteNonQuery();
            if($isConnOpen)
            {
                $conn->close();
            }
        }
        catch(Exception $e)
        {
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
    }
    /**
     * Save models batch.
     * 
     * @param    Fynd_Model_List $models    
     * @param 	 Fynd_Db_IConnection $conn
     * @return   int
     */
    public function saveBatch(Fynd_Model_List $models,Fynd_Db_IConnection $conn)
    {
        $conn = ($conn == null) ? $this->userDbConn : $conn;
        $isConnOpen = false;
        $isTransBegion = false;
        try 
        {
            $isConnOpen = $conn->open();
            $isTransBegion = $conn->beginTrans();
            foreach($models as $model)
            {
                $this->save($model,$conn);
            }
            if($isTransBegion)
            {
                $conn->commit();    
            }
            if($isConnOpen)
            {
                $conn->close();
            }
        }
        catch (Exception $e)
        {
            if($isTransBegion)
            {
                $conn->rollback();   
            }
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
    }
    /**
     * Delete the model.
     * 
     * @param    Fynd_Model $model    
     * @param 	 Fynd_Db_IConnection $conn
     * @return   void
     */
    public function delete(Fynd_Model $model,Fynd_Db_IConnection $conn = null)
    {
        $delete = new Fynd_Model_Delete($model);
        $sql = $delete->createSQL();
        
        $entities = $model->getEntites();
        $primaryProperty = $model->getMeta()->getPrimaryProperty();
        $primaryEntity = $entities[$primaryProperty];
        
        $param = new Fynd_Db_Parameter();
        $param->dataType = $primaryEntity->getDataType();
        $param->name = "p_" . $model->getMeta()->getPrimaryKey();
        $param->value = $model->getPropertyValue($primaryProperty);
        
        $conn = ($conn == null) ? $this->userDbConn : $conn;
        
        $cmd = $conn->createCommand($sql);
        $cmd->addParameter($param);
        
        $affected = 0;
        try
        {
            $isConnOpen = $conn->open();
            $affected = $cmd->excuteNonQuery();
            if($isConnOpen)
            {
                $conn->close();
            }
        }
        catch (Exception $e)
        {
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
        return $affected;
    }
    /**
     * Delete models in model list,return the affected rows count in database.
     * 
     * @param    Fynd_Model_List $models    
     * @param	 Fynd_Db_IConnection $conn
     * @return   int
     */
    public function deleteBatch(Fynd_Model_List $models,Fynd_Db_IConnection $conn = null)
    {
        $conn = ($conn == null) ? $this->userDbConn : $conn;
        $isConnOpen = false;
        $isTransBegion = false;
        $affected = 0;
        try 
        {
            $isConnOpen = $conn->open();
            $isTransBegion = $conn->beginTrans();
            foreach($models as $model)
            {
                $deleted = $this->delete($model,$conn);
                $affected += $deleted;
            }
            if($isTransBegion)
            {
                $conn->commit();    
            }
            if($isConnOpen)
            {
                $conn->close();
            }
        }
        catch (Exception $e)
        {
            if($isTransBegion)
            {
                $conn->rollback();   
            }
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
        return $affected;
    }
    /**
     * Delete model(s) by custom filter
     *
     * @param Fynd_Type $mt
     * @param unknown_type $filterString
     * @param Fynd_Db_IConnection $conn
     * @return int
     */
    public function deleteByFilter(Fynd_Type $mt,$filterString,Fynd_Db_IConnection $conn = null)
    {
        $model = Fynd_Model_Factory::createModel($mt);
        $filter = new Fynd_Model_Filter();
        $filter->setExpression($filterString);
        $filterList = new Fynd_List();
        $filterList->add($filter);
        $where = new Fynd_Model_Where($mt,$filterList);
        $delete = new Fynd_Model_Delete($model,$where);
        $sql = $delete->createSQL();
        
        $conn = ($conn == null) ? $this->userDbConn : $conn;
        $cmd = $conn->createCommand($sql);
        
        $isConnOpen = false;
        $affected = 0;
        try
        {
            $isConnOpen = $conn->open();
            $affected = $cmd->excuteNonQuery(); 
            if($isConnOpen)
            {
                $conn->close();
            }   
        }
        catch (Exception $e)
        {
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
        return $affected;
    }
    /**
     * @return   void
     */
    public function init()
    {
    }
}
?>