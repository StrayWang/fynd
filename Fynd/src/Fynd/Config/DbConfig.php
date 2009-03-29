<?php
//
// +------------------------------------------------------------------------+
// | PHP Version 5                                                          |
// +------------------------------------------------------------------------+
// | Copyright (c) All rights reserved.                                     |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
// | Author:                                                                |
// +------------------------------------------------------------------------+
//
// $Id: DbConfig.php,v 1.3 2008/05/13 15:46:46 administrator Exp $
//

require_once('Fynd/Config/AbstractConfig.php');
/**
* 数据库配置类
* @author       Fishtress
*/
class Fynd_Config_DbConfig extends Fynd_Config_AbstractConfig
{

	/**
    * 数据库连接配置集合
    * @var      array
    */
	protected $_connections;

	/**
    * 默认数据路连接配置
    * @var      Fynd_Config_DbConnectionConfig
    */
	protected $_defaultConnection;

	/**
    * 初始化配置
    * 
    * @access protected
    * @return   void
    */
	protected function _initConfig()
	{
		$this->_connections = array();
		foreach ($this->_rawXmlDoc->DbConfig->Connections->Connection as $dbConn)
		{
			$dbType = eval("return " . (string)$dbConn['Type'] . ";");
			$connName = (string)$dbConn['Name'];
			$this->_addDbConnectionConfig($connName,$dbType,$dbConn);
		}
		$defaultConnConfigName = (string)$this->_rawXmlDoc->DbConfig->DefaultConnection;
		$this->_setDefaultDbConnectionConfig($defaultConnConfigName);
	}
	/**
    * 添加数据库连接配置对象到集合
    * @param    string 			$connName    	连接配置的名称
    * @param    string 			$dbType    		数据库类型
    * @param    SimpleXMLElement $connConfig    数据库类连接原始配置
    * @access 	protected
    * @return   boolean
    * @exception Exception
    */
	protected function _addDbConnectionConfig($connName, $dbType, SimpleXMLElement $connConfig)
	{
		if(key_exists($connName,$this->_connections)) return false;

		if($dbType == Fynd_Db_Type::ORACLE)
		{
		    $type = new Fynd_Type('Fynd_Config_OracleConnectionConfig');
		    $type->IncludeTypeDefinition();
			$this->_connections[$connName]
			        = new Fynd_Config_OracleConnectionConfig((string)$connConfig->Server,
			                (string)$connConfig->Port,
			                (string)$connConfig->User,
			                (string)$connConfig->Password,
			                (string)$connConfig->SID,
			                $dbType);
		}
		else if($dbType == Fynd_Db_Type::SQLITE)
		{
		    $type = new Fynd_Type('Fynd_Config_SQLiteConnectionConfig');
		    $type->includeTypeDefinition();
			$this->_connections[$connName]
			        = new Fynd_Config_SQLiteConnectionConfig((string)$connConfig->DataBaseFilePath);
		}
		else {
		    $type = new Fynd_Type('Fynd_Config_DbConnectionConfig');
		    $type->includeTypeDefinition();
			$this->_connections[$connName]
			        = new Fynd_Config_DbConnectionConfig((string)$connConfig->Server,
			                (string)$connConfig->Port,
			                (string)$connConfig->User,
			                (string)$connConfig->Password,
			                (string)$connConfig->DataBase,
			                $dbType);
		}
		return true;
	}
	/**
    * 设置默认数据库连接配置
    * @param    string $defaultConnConfigName    默认数据库连接配置名称
    * @access protected
    * @return   void
    * @exception Exception
    */
	protected function _setDefaultDbConnectionConfig($defaultConnConfigName)
	{
		if(key_exists($defaultConnConfigName,$this->_connections))
		{
			$this->_defaultConnection = $this->_connections[$defaultConnConfigName];
		}
		else
		{
		    Fynd_Object::throwException("Fynd_Config_Exception","Default connection configure name is not in kown connection configure names");
		}
	}
	/**
    * 获取指定数据库连接配置
    * @param    string $connectionName
    * @access public    
    * @return   Fynd_Config_DbConnectionConfig
    */
	public function getConnectionConfig($connectionName)
	{
		return $this->_connections[$connectionName];
	}

	/**
    * 获取默认数据库连接配置
    * 
    * @access public
    * @return   Fynd_Config_DbConnectionConfig
    */
	public function getDefaultConnectionConfig()
	{
		return $this->_defaultConnection;
	}
}

?>