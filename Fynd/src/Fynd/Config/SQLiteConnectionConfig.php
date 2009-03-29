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
// $Id: SQLiteConnectionConfig.php,v 1.1 2008/05/13 15:47:22 administrator Exp $
//
require_once('Fynd/Config/DbConnectionConfig.php');

/**
* 针对SQLite数据库的详细配置，只有getDataBaseFilePath方法能获取值，其他的方法均返回空值
* @author       Fishtress
*/
class Fynd_Config_SQLiteConnectionConfig extends Fynd_Config_DbConnectionConfig
{

	/**
    * SQLite数据库文件路径
    * @var      string
    */
	protected $_databaseFilePath;
	/**
	 * 构造函数
	 *
	 * @param string $dbFilePath 数据库文件路径
	 */
	public function __construct($dbFilePath)
	{
		$this->_databaseFilePath = $dbFilePath;
	}
	/**
    * 获取数据库文件路径
    * @return   string
    */
	public function getDataBaseFilePath()
	{
		return $this->_databaseFilePath;
	}
}

?>