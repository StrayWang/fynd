<?php
require_once 'Fynd/Model.php';
class FieldModel extends Fynd_Model
{
    /**
     * @var string
     */
    protected $_name;
    /**
     * @var int
     */
    protected $_dataType;
    /**
     * @var int
     */
    protected $_length;
    /**
     * @var bool
     */
    protected $_isNullable = true;
    /**
     * @var bool
     */
    protected $_isPrimaryKey = false;
    /**
     * @var string
     */
    protected $_key;
    /**
     * @var string
     */
    protected $_default;
    /**
     * @var string
     */
    protected $_extra;
    /**
     * @return int
     */
    public function getDataType()
    {
        return $this->_dataType;
    }
    /**
     * @param int $_dataType
     */
    public function setDataType($_dataType)
    {
        $this->_dataType = $_dataType;
    }
    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->_default;
    }
    /**
     * @param string $_default
     */
    public function setDefault($_default)
    {
        $this->_default = $_default;
    }
    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->_extra;
    }
    /**
     * @param string $_extra
     */
    public function setExtra($_extra)
    {
        $this->_extra = $_extra;
    }
    /**
     * @return bool
     */
    public function getIsNullable()
    {
        return $this->_isNullable;
    }
    /**
     * @param bool $_isNullable
     */
    public function setIsNullable($_isNullable)
    {
        $this->_isNullable = $_isNullable;
    }
    /**
     * @return bool
     */
    public function getIsPrimaryKey()
    {
        return $this->_isPrimaryKey;
    }
    /**
     * @param bool $_isPrimaryKey
     */
    public function setIsPrimaryKey($_isPrimaryKey)
    {
        $this->_isPrimaryKey = $_isPrimaryKey;
    }
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }
    /**
     * @param string $_key
     */
    public function setKey($_key)
    {
        $this->_key = $_key;
    }
    /**
     * @return int
     */
    public function getLength()
    {
        return $this->_length;
    }
    /**
     * @param int $_length
     */
    public function setLength($_length)
    {
        $this->_length = $_length;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    /**
     * @param string $_name
     */
    public function setName($_name)
    {
        $this->_name = $_name;
    }
    /**
     * @see Serializable::serialize()
     *
     */
    public function serialize()
    {}
    /**
     * @see Serializable::unserialize()
     *
     * @param serialized $serialized
     */
    public function unserialize($serialized)
    {}
}
?>