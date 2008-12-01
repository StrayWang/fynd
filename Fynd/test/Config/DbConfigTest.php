<?php
require_once 'Fynd/Config/DbConfig.php';
require_once 'Fynd/Config/ConfigManager.php';
require_once 'PHPUnit/Framework/TestCase.php';
/**
 * Fynd_Config_DbConfig test case.
 */
class Fynd_Config_DbConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests Fynd_Config_DbConfig->getConnectionConfig()
     */
    public function testGetConnectionConfig ()
    {
        $configXml = include 'ConfigXmlForInclude.php';
        try
        {           
            $dbConfig = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig,$configXml);
            $dbConnectionConfig = $dbConfig->getConnectionConfig('主机1');
            $this->assertType('Fynd_Config_DbConnectionConfig', $dbConnectionConfig);
            $this->assertEquals('127.0.0.1',$dbConnectionConfig->getServer());
            $this->assertEquals('3306',$dbConnectionConfig->getPort());
            $this->assertEquals('simplespace',$dbConnectionConfig->getDataBase());
            $this->assertEquals('test',$dbConnectionConfig->getUser());
            $this->assertEquals('123456',$dbConnectionConfig->getPassword());            
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
    /**
     * Tests Fynd_Config_DbConfig->getDefaultConnectionConfig()
     */
    public function testGetDefaultConnectionConfig ()
    {
        $configXml = include 'ConfigXmlForInclude.php';
        try
        {           
            $dbConfig = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig,$configXml);
            $dbConnectionConfig = $dbConfig->getDefaultConnectionConfig();
            $this->assertType('Fynd_Config_DbConnectionConfig', $dbConnectionConfig);
            $this->assertEquals('127.0.0.1',$dbConnectionConfig->getServer());
            $this->assertEquals('3306',$dbConnectionConfig->getPort());
            $this->assertEquals('simplespace',$dbConnectionConfig->getDataBase());
            $this->assertEquals('test',$dbConnectionConfig->getUser());
            $this->assertEquals('123456',$dbConnectionConfig->getPassword());            
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
    
    public function testGetOracleConnectionConfig()
    {
        $configXml = include 'ConfigXmlForInclude.php';
        try
        {           
            $dbConfig = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig,$configXml);
            $dbConnectionConfig = $dbConfig->getConnectionConfig('主机4');
            $this->assertType('Fynd_Config_OracleConnectionConfig', $dbConnectionConfig);
            $this->assertEquals('TEST',$dbConnectionConfig->getSID());   
            $this->assertEquals('dev.cn',$dbConnectionConfig->getServer());
            $this->assertEquals('1521',$dbConnectionConfig->getPort());
            $this->assertEquals('MY_TEST',$dbConnectionConfig->getUser());
            $this->assertEquals('123456',$dbConnectionConfig->getPassword());     
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }        
    }
    
    public function testGetSQLiteConnectionConfig()
    {
        $configXml = include 'ConfigXmlForInclude.php';
        try
        {           
            $dbConfig = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig,$configXml);
            $dbConnectionConfig = $dbConfig->getConnectionConfig('主机5');
            $this->assertType('Fynd_Config_SQLiteConnectionConfig', $dbConnectionConfig);
            $this->assertEquals('/path/to/sqlite/database/file',$dbConnectionConfig->getDataBaseFilePath());     
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
}

