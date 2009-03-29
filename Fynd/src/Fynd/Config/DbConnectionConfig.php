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
// | Author:fishtrees                                                                |
// +------------------------------------------------------------------------+
//
// $Id: DbConnectionConfig.php,v 1.1 2008/05/13 15:47:22 administrator Exp $
//


/**
* 数据库配置细节
* @author       Fishtress
*/
class Fynd_Config_DbConnectionConfig
{

	/**
    * 数据库服务器
    * @var      string
    */
	protected $_server;

	/**
    * 数据库服务器端口
    * @var      string
    */
	protected $_port;

	/**
    * 登录数据库服务器的用户名
    * @var      string
    */
	protected $_user;

	/**
    * 登录数据库服务器的密码
    * @var      string
    */
	protected $_password;

	/**
    * 要使用的数据库
    * @var      string
    */
	protected $_database;

	/**
    * 数据库服务器类型
    * @var      string
    */
	protected $_dbType;

	/**
    * 构造函数
    * @param    string $server    服务器地址
    * @param    string $port    端口
    * @param    string $user    用户名
    * @param    string $password    密码
    * @param    string $database    数据库或Oracle的SID
    * @param    string $dbType    数据库服务器类型
    * @return   void
    */
	public function __construct($server, $port, $user, $password, $database, $dbType)
	{
		$this->_server = $server;
		$this->_port = $port;
		$this->_user = $user;
		$this->_password = $password;
		$this->_database = $database;
		$this->_dbType = $dbType;
	}

	/**
    * 获取数据库服务器
    * @return   string
    */
	public function getServer()
	{
		return $this->_server;
	}

	/**
    * 获取数据库服务器端口
    * @return   string
    */
	public function getPort()
	{
		return $this->_port;
	}

	/**
    * 获取登录数据库服务器的用户名
    * @return   string
    */
	public function getUser()
	{
		return $this->_user;
	}

	/**
    * 获取登录数据库服务器的密码
    * @return   string
    */
	public function getPassword()
	{
		return $this->_password;
	}

	/**
    * 获取要连接的数据库
    * @return   string
    */
	public function getDatabase()
	{
		return $this->_database;
	}

	/**
    * 获取数据库服务器类型，
    * Described by Fynd_Db_Type
    * @return   string
    */
	public function getDbType()
	{
		return $this->_dbType;
	}
}

?>