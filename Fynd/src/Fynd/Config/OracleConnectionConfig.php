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
// | Author:fishtrees                                                       |
// +------------------------------------------------------------------------+
//
// $Id: OracleConnectionConfig.php,v 1.1 2008/05/13 15:47:22 administrator Exp $
//
require_once('Fynd/Config/DbConnectionConfig.php');

/**
* 针对Oracle数据库的详细配置
* @author       Fishtress
*/
class Fynd_Config_OracleConnectionConfig extends Fynd_Config_DbConnectionConfig
{

	/**
    * Oracle SID
    * @var      string
    */
	protected $_sid;

	/**
    * 构造函数
    * @param    string $server    
    * @param    string $port    
    * @param    string $user    
    * @param    string $password    
    * @param    string $database    
    * @param    string $dbType    
    * @return   void
    */
	public function __construct($server, $port, $user, $password, $database, $dbType)
	{
		parent::__construct($server, $port, $user, $password, $database, $dbType);
		$this->_sid = $database;
	}
	/**
    * 获取Oracle的SID
    * @return   string
    */
	public function getSID()
	{
		return $this->_sid;
	}
}

?>