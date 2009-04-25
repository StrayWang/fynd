<?php
require_once 'Fynd/Object.php';
final class Fynd_Type extends Fynd_Object
{
    private $_className;
    private $_definitionFilePath;
    public function __construct($className)
    {
        $this->_className = trim($className);
    }
    /*
     * @return Fynd_Object
     */
    public function getClassName()
    {
        return $this->_className;
    }
    /**
     * Create a instance of the type,
     * this method will use the default constractor to create the instance
     * 
     * @return Fynd_Object
     */
    public function createInstance()
    {
        $className = $this->_className;
        $this->includeTypeDefinition();
        $obj = new $className();
        return $obj;
    }
    /**
     * Include the definition file of the type,make the type available in the execution context.
     *
     * @param string $searchPath The path where can find the definition file,end with "/"
     */
    public function includeTypeDefinition($searchPath = "")
    {
        if(! Fynd_StringUtil::StartWith($this->_className, 'Fynd_') && Fynd_StringUtil::endWith($this->_className, "Ctrl"))
        {
            @include_once Fynd_Env::getCtrlPath() . $this->_className . ".php";
        }
        else if(! Fynd_StringUtil::StartWith($this->_className, 'Fynd_') && Fynd_StringUtil::endWith($this->_className, "View"))
        {
            @include_once Fynd_Env::getViewPath() . $this->_className . ".php";
        }
        else if(! Fynd_StringUtil::StartWith($this->_className, 'Fynd_') && Fynd_StringUtil::endWith($this->_className, "Model"))
        {
            @include_once Fynd_Env::getModelPath() . $this->_className . ".php";
        }
        else if(! Fynd_StringUtil::StartWith($this->_className, 'Fynd_') && Fynd_StringUtil::endWith($this->_className, "Service"))
        {
            @include_once Fynd_Env::getServicePath() . $this->_className . ".php";
        }
        else
        {
            $parts = split('_', $this->_className);
            if(is_array($parts) && count($parts) > 1)
            {
                $file = "";
                for($i = 0; $i < count($parts) - 1; $i ++)
                {
                    $file .= $parts[$i] . '/';
                }
                $file .= $parts[count($parts) - 1] . '.php';
            }
            else
            {
                $file = $file . '.php';
            }
            if(empty($searchPath))
            {
                @include_once ($file);
            }
            else
            {
                @include_once ($searchPath . $file);
            }
        }
        if(! class_exists($this->_className))
        {
            throw new Exception($this->_className . '\'s definition file can not be loaded,it is ' . $file);
        }
    }
    /**
     * Create ReflectionClass instance of the type.
     *
     * @return ReflectionClass
     */
    public function getReflection()
    {
        $ref = new ReflectionClass($this->_className);
        return $ref;
    }
    /**
     * Checks the given type existent or not
     *
     * @param string $typeName
     * @return bool
     */
    public static function isTypeExistent($typeName)
    {
        $existent = false;
        $type = new Fynd_Type($typeName);
        try 
        {
            $type->includeTypeDefinition();
            $existent = true;
        }
        catch (Exception $e)
        {
            
        }
        return $existent;
    }
}
?>