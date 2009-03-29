<?php
require_once 'Fynd/StringUtil.php';
class Fynd_Object
{
    private $_hashCode;
    /**
     * Allow dynamic create a class field or not
     *
     * @var bool
     */
    private $_allowDynamicField = false;
    /**
     * Sets the identity to allow dynamic field or not.
     *
     * @param bool $allow
     */
    public function setAllowDynamicField($allow)
    {
        $this->_allowDynamicField = $allow;
    }
    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        if($this->_allowDynamicField)
        {
            $this->$key = $value;
        }
        else
        {
            throw new Exception("$key is non-existent field.");
        }
    }
    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        throw new Exception("$key is non-existent field.");
    }
    public function __call($name,$args)
    {
        throw new Exception("Method $name with " 
            . count($args) 
            . " parameters is non-existent.");
    }
    /**
     * Get the type of Fynd framework.
     *
     * @return Fynd_Type
     */
    public function getType()
    {
        $ref = $this->getReflectionObject();
        $type = new Fynd_Type($ref->getName());
        return $type;
    }
    /**
     * Get a Reflection use PHP reflection furture.
     *
     * @return ReflectionObject
     */
    public function getReflectionObject()
    {
        $ref = new ReflectionObject($this);
        return $ref;
    }
    /**
     * Returns the hash of the unique identifier for the object.
     *
     * @return string
     */
    public function getHashCode()
    {
        if(empty($this->_hashCode))
        {
            if(! function_exists('spl_object_hash'))
            {
                $dump = var_export($this, true);
                $match = array();
                if(preg_match('/^object\(([a-z0-9_]+)\)\#(\d)+/i', $dump, $match))
                {
                    $this->_hashCode = md5($match[1] . $match[2]);
                }
            }
            else 
            {
                $this->_hashCode = spl_object_hash($this);
            }
        }
        
        return $this->_hashCode;
    }
    /**
     * Compare $this with the object. 
     *
     * @param Fynd_Object $obj
     * @return bool
     */
    public function equals(Fynd_Object $obj)
    {
        return ($obj == $this);
    }
    /**
     * Throw an exception
     *
     * @param string $expName
     * @param string $msg
     * @param int $code
     */
    public static function throwException($expName,$msg,$code = null)
    {
        $type = new Fynd_Type($expName);
        $type->includeTypeDefinition();
        if(is_numeric($code))
        {
            throw new $expName($msg,$code);
        }
        else 
        {
            throw new $expName($msg . ' ' . $code);
        }
    }
}
?>