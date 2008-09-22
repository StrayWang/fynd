<?php
require_once 'PublicPropertyClass.php';
require_once 'Model/ModelStatus.php';
require_once 'Db.php';
require_once 'Model/ModelEntry.php';
require_once 'Db/DbParameter.php';
abstract class Fynd_Model extends Fynd_PublicPropertyClass implements IteratorAggregate
{
    protected $_fyndStatus;
    protected $_fyndLockStatus = false;
    protected $_fyndModelEntryCollection;
    protected $_fyndTableName;
    protected $_fyndPrimaryProperty;
    public function __construct ()
    {
        $this->_fyndStatus = Fynd_Model_ModelStatus::None;
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
    public function acceptChange ()
    {
        $primary = $this->_fyndPrimaryProperty;
        if (empty($this->$primary))
        {
            $this->_acceptAdded();
        }
        else
        {
        }
    }
    /**
     * 根据过滤条件获取Model或Model集合
     *
     * @param Fynd_Model_ModelSelection | array $condition
     * @param bool 是否只求符合条件的Model数量
     * @return Fynd_Model | array
     */
    public function select ($condition, $isCount)
    {
        $this->_loadMapXml();
        $fetchMode = PDO::FETCH_CLASS;
        if ($isCount)
        {
            $sql = 'Select Count(*) From `' . $this->_fyndTableName . '` ';
            $fetchMode = PDO::FETCH_ASSOC;
        }
        else
        {
            $sql = 'Select * From ' . $this->_fyndTableName;
        }
        if (is_array($condition) && count($condition) > 0)
        {
            $sql .= ' Where ';
            foreach ($condition as $mc)
            {
                $whereClause = $this->_getWhereClause($mc);
                if (! empty($whereClause))
                {
                    $sql .= $whereClause;
                }
            }
        }
        else
        {
            $sql .= ' Where ' . $this->_getWhereClause($condition);
        }
        $db = Fynd_Db::getInstance();
        $db->open();
        $db->setFetchMode($fetchMode);
        $models = $db->query($sql, NULL, $this->getType()->getName());
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
            if ($entry->DataType == 'string')
            {
                $sql .= $mc->Operation . "'" . $mc->ConditionValue . "'";
            }
            else
            {
                $sql .= $mc->Operation . $mc->ConditionValue;
            }
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
        foreach ($properties as $name => $value)
        {
            $entry = $this->_fyndModelEntryCollection[$name];
            $p = new Fynd_DbParameter();
            $p->Name = ':v_' . $name;
            $p->Value = $value;
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
        $db = Fynd_Db::getInstance();
        $db->open();
        $db->excute($sql, $params);
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
        try
        {
            parent::__set($key, $value);
        }
        catch (Exception $e)
        {
            //For Db
            $parts = split('_', $key);
            $parts[1] = Fynd_Util::upperCaseFirstChar($parts[1]);
            $privateVar = implode('', $parts);
            parent::__set($privateVar, $value);
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
    //	public abstract function validate();
//	public abstract function getInsertSql();
//	public abstract function getUpdateSql();
//	public abstract function getSelectSql();
//	public abstract function getDeleteSql();
}
?>