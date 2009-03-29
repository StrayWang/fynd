<?php
require_once 'Fynd/Object.php';
require_once 'Fynd/Model/State.php';
require_once 'Fynd/Model/Entity.php';
require_once 'Fynd/Model/Delete.php';
require_once 'Fynd/Model/Insertion.php';
require_once 'Fynd/Model/Updating.php';
require_once 'Fynd/Db/Parameter.php';
abstract class Fynd_Model extends Fynd_Object implements IteratorAggregate,Serializable
{
    /**
     * @var Fynd_Log
     */
    private static $_log = null;
    /**
     * Model entity collection
     *
     * @var Fynd_Dictionary
     */
    private $_entities;
    /**
     * The meta data of model
     *
     * @var Fynd_Model_Meta
     */
    private $_meta;
    
    public function __construct()
    {
        $this->_entities = new Fynd_Dictionary();
        if(is_null(self::$_log))
        {
            self::$_log = Fynd_Application::getLogger('Fynd_Model');
        }
    }
    /**
     * Get the entities of the model
     *
     * @return Fynd_Dictionary
     */
    public function getEntites()
    {
        return $this->_entities;
    }
    /**
     * @return Fynd_Model_Meta
     */
    public function getMeta()
    {
        return $this->_meta;
    }
    /**
     * Sets the model meta info.
     *
     * @param Fynd_Model_Meta $meta
     */
    public function setMeta(Fynd_Model_Meta $meta)
    {
        $this->_meta = $meta;
    }
    /**
     * Get the model's state
     *
     * @return int
     */
    public function getState()
    {
        return $this->_meta->get_state();
    }
    /**
     * Set the model's state
     *
     * @param int $state
     */
    public function setState($state)
    {
        $this->_meta->setState($state);
    }
    public function addEntity(Fynd_Model_Entity $entity)
    {
        $this->_entities->add($entity->getProperty(), $entity);
    }
    /**
     * IteratorAggregate接口实现
     * @return ArrayObject
     *
     */
    public function getIterator()
    {
        $modelPropertis = array();
        foreach($this->_entities as $entity)
        {
            $property = $entity->getProperty();
            $modelPropertis[$property] = $this->_evalProperty($property);
        }
        return new ArrayObject($modelPropertis);
    }
    /**
     * Get value of model property
     *
     * @param string $name
     * @return mixed
     */
    public function getPropertyValue($name)
    {
        return $this->_evalProperty($name);
    }
    /**
     * Get the primary property(primary key)'s value
     *
     * @return scalar
     */
    public function getPrimaryPropertyValue()
    {
        return $this->_evalProperty($this->_meta->getPrimaryProperty());
    }
    public function setPropertyValue($name, $value)
    {
        $this->_assignProperty($name, $value);
    }
    protected function _evalProperty($name)
    {
        $getterName = "get" . $name;
        try
        {
            return $this->$getterName();
        }
        catch (Exception $e)
        {}
        return null;
    }
    protected function _assignProperty($name, $value)
    {
        $setterName = "set" . $name;
        try
        {
            $this->$setterName($value);
        }
        catch (Exception $e)
        {
            
        }
    }
    /**
     * Create the insert sql from the model
     * 
     * @param 	scalar 		$primaryKeyValue
     * @param 	Fynd_List 	$params Out the parameters in the sql
     * @return string
     */
    public function createInsertSQL($primaryKeyValue, Fynd_List $params = null)
    {
        $exisitPrimaryValue = $this->getPrimaryPropertyValue();
        if(! empty($exisitPrimaryValue))
        {
            throw new Fynd_Model_Exception("The model already have a primary value,can not be inserted.");
        }
        $insertion = new Fynd_Model_Insertion($this);
        $sql = $insertion->createSQL();
        if($params != null)
        {
            $primaryProperty = $this->_meta->getPrimaryProperty();
            foreach($this->_entities as $property => $entity)
            {
                $param = new Fynd_Db_Parameter();
                $field = $entity->getField();
                if($field == $primaryProperty)
                {
                    $this->_assignProperty($primaryProperty, $primaryKeyValue);
                    $param->Value = $primaryProperty;
                }
                else
                {
                    $param->Value = $this->_evalProperty($property);
                }
                $param->dataType = $entity->getDataType();
                $param->Name = "p_" . $field;
                $params->add($param);
            }
        }
        return $sql;
    }
    /**
     * Create the update sql from the model,
     * return a sql when the model contains one or more entites that have been modified,
     * otherwise,it return a empty string
     * 
     * @param 	Fynd_List $params Out the parameters in the sql
     * @return	string
     */
    public function createUpdateSQL(Fynd_List $params = null)
    {
        $update = new Fynd_Model_Updating($this);
        $sql = $update->createSQL();
        if(empty($sql))
        {
            return "";
        }
        
        $primaryProperty = $this->_meta->getPrimaryProperty();
        foreach($this->_entities as $property => $entity)
        {
            //ignore the non-modified fields and primary key
            if($entity->getState() != Fynd_Model_State::Modified || $property == $primaryProperty)
            {
                continue;
            }
            $param = new Fynd_Db_Parameter();
            $param->dataType = $entity->getDataType();
            $param->Name = "p_" . $entity->getField();
            $param->Value = $this->_evalProperty($property);
            $params->add($param);
        }
        //Add the primary key parameter in where clause
        $primaryEntity = $this->_entities[$primaryProperty];
        $param = new Fynd_Db_Parameter();
        $param->dataType = $primaryEntity->getDataType();
        $param->Name = "p_" . $primaryEntity->getField();
        $param->Value = $this->_evalProperty($primaryProperty);
        $params->add($param);
        
        return $sql;
    }
    /**
     * Create the delete sql from the model
     * 
     * @param Fynd_List $params Out the parameters in the sql
     * @return   string
     */
    public function createSelectSQL(Fynd_List $params = null)
    {
        $delete = new Fynd_Model_Delete($this);
        $sql = $delete->createSQL();
        
        //Add the primary key parameter in where clause
        $primaryProperty = $this->_meta->getPrimaryProperty();
        $primaryEntity = $this->_entities[$primaryProperty];
        $param = new Fynd_Db_Parameter();
        $param->dataType = $primaryEntity->getDataType();
        $param->Name = "p_" . $primaryEntity->getField();
        $param->Value = $this->_evalProperty($primaryProperty);
        $params->add($param);
        
        return $sql;
    }
}
?>