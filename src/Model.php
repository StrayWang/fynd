<?php
require_once 'PublicPropertyClass.php';
require_once 'Model/ModelStatus.php';
require_once 'Db.php';
require_once 'Model/ModelEntry.php';
require_once 'Db/DbParameter.php';
abstract class Fynd_Model 
    extends Fynd_PublicPropertyClass 
    implements IteratorAggregate
{
    protected $_fyndStatus;
    protected $_fyndInitializing = false;
    protected $_fyndModelEntryCollection;
    protected $_fyndTableName;
    protected $_fyndPrimaryProperty;
    public function __construct ()
    {
        $this->_fyndStatus = Fynd_Model_ModelStatus::None;
        $this->_loadMapXml();
    }
    public function beginInitializtion()
    {
        $this->_fyndInitializing = true;
    }
    public function endInitializtion()
    {
        $this->_fyndInitializing = false;
    }
    public function getStatus ()
    {
        return $this->_fyndStatus;
    }
    public function setStatus ($status)
    {
        if ($status != Fynd_Model_ModelStatus::Added || $status != Fynd_Model_ModelStatus::Deleted || $status != Fynd_Model_ModelStatus::Modified || $status != Fynd_Model_ModelStatus::None)
        {
            throw new Exception('$status参数不是有效值');
        }
        $this->_fyndStatus = $status;
    }
    /**
     * Save model data to database
     *
     */
    public function save ()
    {
        $primary = $this->_fyndPrimaryProperty;
        $primaryValue = $this->$primary;
        if (empty($primaryValue))
        {
            $this->_acceptAdded();
        }
        else
        {
            $this->_acceptModified();
        }
    }
    /**
     * Delete model data from database
     *
     */
    public function delete()
    {
        $primaryProperty = $this->_fyndPrimaryProperty;
        $entry = $this->_fyndModelEntryCollection[$primaryProperty];
        $p = new Fynd_DbParameter();
        $p->Name = ':v_'.$entry->Property;
        $p->Value = $this->$primaryProperty;
        if($entry->DataType == 'number')
        {
            $p->DbDataType = PDO::PARAM_INT;
        }
        else 
        {
            $p->DbDataType = PDO::PARAM_STR;
        }
        $sql = "Delete From `".$this->_fyndTableName."` Where ".$entry->Field." = ".$p->Name;
        $db = Fynd_Db::getInstance();
        $db->open();
        $db->excute($sql,array($p));
        $db->close();
    }
    /**
     * 根据过滤条件获取Model或Model集合
     *
     * @param Fynd_Model_ModelSelection | array $condition
     * @param bool 是否只求符合条件的Model数量
     * @return Fynd_Model | array
     */
    public function select ($condition, $isCount = false)
    {
        $fetchMode = PDO::FETCH_CLASS;
        if ($isCount)
        {
            $sql = 'Select Count(*) From `' . $this->_fyndTableName . '` ';
            $fetchMode = PDO::FETCH_ASSOC;
        }
        else
        {
            $sql = 'Select * From `' . $this->_fyndTableName .'` ';
        }
        $params = array();
        if (is_array($condition) && count($condition) > 0)
        {
            $sql .= ' Where ';
            foreach ($condition as $mc)
            {
                $whereClause = $this->_getWhereClause($mc);
                if (! empty($whereClause))
                {
                    $sql .= $whereClause;
                    if (!is_array($mc->ConditionValue))
                    {
                        $p = new Fynd_DbParameter();
                        $entry = $this->_fyndModelEntryCollection[$mc->Property];
                        $p->Name = ':v_'.$entry->Property;
                        $p->Value = $mc->ConditionValue;
                        if($entry->DataType != 'number')
                        {
                            $p->DbDataType = PDO::PARAM_STR;
                        }
                        else 
                        {
                            $p->DbDataType = PDO::PARAM_INT;
                        }
                        $params[] = $p;
                    }
                }
            }
        }
        else if($condition instanceof Fynd_Model_ModelSelection )
        {
            $sql .= ' Where ' . $this->_getWhereClause($condition);
            if (!is_array($condition->ConditionValue))
            {
                $p = new Fynd_DbParameter();
                $entry = $this->_fyndModelEntryCollection[$condition->Property];
                $p->Name = ':v_'.$entry->Property;
                $p->Value = $condition->ConditionValue;
                if($entry->DataType != 'number')
                {
                    $p->DbDataType = PDO::PARAM_STR;
                }
                else 
                {
                    $p->DbDataType = PDO::PARAM_INT;
                }
                $params[] = $p;
            }
        }

        $db = Fynd_Db::getInstance();
        $db->open();
        $db->setFetchMode($fetchMode);
        $models = $db->query($sql, $params, $this->getType()->getName());
        $db->close();

        return $models;
    }
    protected function _getWhereClause (Fynd_Model_ModelSelection $mc)
    {
        $entry = $this->_fyndModelEntryCollection[$mc->Property];
        $sql = '';
        if (! empty($mc->LeftBrackets))
        {
            $sql .= ' ' . $mc->LeftBrackets;
        }
        $sql .= $entry->Field;
        if (is_array($mc->ConditionValue))
        {
            $sql .= 'In (';
            foreach ($mc->ConditionValue as $value)
            {
                if ($entry->DataType == 'string')
                {
                    $sql .= "'" . $value . "',";
                }
                else
                {
                    $sql .= $value . ",";
                }
            }
            $sql = Fynd_Util::stringRemoveEnd($sql, 1);
            $sql .= ')';
        }
        else
        {
            $sql .= $mc->Operation . ":v_" . $mc->Property;           
        }
        if (! empty($mc->NextLogicOperater))
        {
            $sql .= ' ' . $mc->NextLogicOperater;
        }
        else if (! empty($mc->RightBrackets))
        {
            $sql .= $mc->RightBrackets;
        }
        return $sql;
    }
    protected function _acceptAdded ()
    {
        $properties = $this->getIterator();
        $sql = "Insert Into " . $this->_fyndTableName . ' (';
        $params = array();
        $db = Fynd_Db::getInstance();
        foreach ($properties as $name => $value)
        {
            $entry = $this->_fyndModelEntryCollection[$name];
            $p = new Fynd_DbParameter();
            $p->Name = ':v_' . $name;
            if($name == $this->_fyndPrimaryProperty)
            {
               $p->Value = $db->getNextId($entry->Field); 
            }
            else 
            {
                $p->Value = $value;
            }
            $fields .= '`' . $entry->Field . '`,';
            $values .= $p->Name . ",";
            if ($entry->DataType != 'number')
            {
                $p->DbDataType = PDO::PARAM_STR;
            }
            else
            {
                $p->DbDataType = PDO::PARAM_INT;
            }
            $params[] = $p;
        }
        $fields = Fynd_Util::stringRemoveEnd($fields, 1);
        $values = Fynd_Util::stringRemoveEnd($values, 1);
        $sql .= $fields . ') Values (' . $values . ')';
        
        $db->open();
        $db->excute($sql, $params);
        $db->close();
    }
    protected function _acceptModified()
    {
        $properties = $this->getIterator();
        $sql = "Update `" . $this->_fyndTableName . '` As t Set ';
        $params = array();
        $db = Fynd_Db::getInstance();
        foreach ($properties as $name => $value)
        {
            $entry = $this->_fyndModelEntryCollection[$name];
            $p = new Fynd_DbParameter();
            $p->Name = ':v_' . $name;
            if($name == $this->_fyndPrimaryProperty)
            {
               $where = " Where t.".$entry->Field." = ".$p->Name;
               $p->Value = $value;
            }
            else 
            {
                $set .= "t.".$entry->Field." = ".$p->Name.",";
                $p->Value = $value;
            }
            
            if ($entry->DataType != 'number')
            {
                $p->DbDataType = PDO::PARAM_STR;
                echo $entry->DataType."\n";
            }
            else
            {
                $p->DbDataType = PDO::PARAM_INT;
                echo $entry->DataType."\n";
            }
            $params[] = $p;
        }
        $set = Fynd_Util::stringRemoveEnd($set,1);
        $sql .= $set . $where;
        
        $db->open();
        $db->excute($sql,$params);
        $db->close();
    }
    protected function _setModelStatus ()
    {
        if ($this->_fyndStatus == Fynd_Model_ModelStatus::None)
        {
            $primary = $this->_fyndPrimaryProperty;
            if (! empty($this->$primary))
            {
                $this->_fyndStatus = Fynd_Model_ModelStatus::Modified;
            }
            else
            {
                $this->_fyndStatus = Fynd_Model_ModelStatus::Added;
            }
        }
    }
    protected function _loadMapXml ()
    {
        $ref = new ReflectionObject($this);
        $filename = str_replace('.php', '.xml', $ref->getFileName());
        $xml = simplexml_load_file($filename);
        $this->_fyndTableName = (string) $xml['Table'];
        $this->_fyndPrimaryProperty = (string) $xml['PrimaryProperty'];
        $this->_fyndModelEntryCollection = array();
        foreach ($xml as $node)
        {
            $entry = new Fynd_Model_ModelEntry();
            $entry->Property = (string) $node->Property;
            $entry->Field = (string) $node->Field;
            $entry->DataType = (string) $node->DataType;
            $entry->DataLength = (string) $node->DataLength;
            $this->_fyndModelEntryCollection[$entry->Property] = $entry;
        }
    }
    public function __set ($key, $value)
    {
        if($this->_fyndInitializing)
        {
            //For Db
            $parts = split('_', $key);
            for($i=1;$i<count($parts);$i++)
            {
                $parts[$i] = Fynd_Util::upperCaseFirstChar($parts[$i]);
            }
            $privateVar = implode('', $parts);
            $privateVar = '_'.$privateVar;
            if(!$this->getType()->getProperty($privateVar))
            {
                throw new Exception("Property '".$privateVar."' does not exist");
            }
            $this->$privateVar = $value;
        }
        else 
        {
            parent::__set($key, $value);
        }
    }
    /**
     * IteratorAggregate接口实现
     * @return ArrayObject
     *
     */
    public function getIterator ()
    {
        $type = $this->getType();
        $propertis = $type->getProperties();
        $modelPropertis = array();
        foreach ($propertis as $p)
        {
            $propertyName = $p->name;
            if (Fynd_Util::startWith($propertyName, '_fynd'))
            {
                continue;
            }
            if(Fynd_Util::startWith($propertyName,'_'))
                $propertyName = str_replace('_','',$propertyName);
            $propertyName = Fynd_Util::upperCaseFirstChar($propertyName);                
            $modelPropertis[$propertyName] = $this->$propertyName;
        }
        return new ArrayObject($modelPropertis);
    }

}
?>