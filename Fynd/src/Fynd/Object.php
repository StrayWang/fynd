<?php
require_once 'Util.php';
class Fynd_Object
{
	/**
	 * 设置公共属性值
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key,$value)
	{
		$privateVar = Fynd_Util::convertToPrivateVar($key);
		$setter = 'set'.$key;
		if(method_exists($this,$setter))
		{
			$this->$setter($value);
		}
		else if(!$this->getType()->getProperty($privateVar))
		{
			throw new Exception('property:'.$key." does not exsist");
		}
		else 
		{
			$this->$privateVar = $value;
		}
	}
	/**
	 * 获取公共属性值
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
	    //TODO:私有属性如_foobar也可以通过该方法访问，应该屏蔽
		$privateVar = Fynd_Util::convertToPrivateVar($key);
		$getter = 'get'.$key;
		$type = $this->getType();
		try 
		{
		    $method = $type->getMethod($getter);
		}
		catch (Exception $e)
		{
		}
		if($method && !$method->isStatic())
		{
			return $this->$getter($key);
		}
		else if(!$type->getProperty($privateVar))
		{
			throw new Exception('property:'.$key." does not exsist");
		}
		else 
		{
			return $this->$privateVar;
		}
	}
	/**
	 * 获取类型
	 *
	 * @return ReflectionObject
	 */
	public function getType()
	{
		$type = new ReflectionObject($this);
		return $type;
	}
}
?>