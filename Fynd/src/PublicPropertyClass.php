<?php
require_once 'PublicPropertyClass.php';
require_once 'Util.php';
abstract class Fynd_PublicPropertyClass
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
		else if(!property_exists($this->getType()->getName(),$privateVar))
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
		$privateVar = Fynd_Util::convertToPrivateVar($key);
		$getter = 'get'.$key;
		if(method_exists($this,$getter))
		{
			return $this->$getter($key);
		}
		else if(!isset($this->$privateVar))
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