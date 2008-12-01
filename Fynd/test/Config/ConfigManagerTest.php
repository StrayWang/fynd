<?php
require_once 'Fynd/Config/ConfigType.php';
require_once 'Fynd/Config/ConfigManager.php';
require_once 'PHPUnit/Framework/TestCase.php';
/**
 * Fynd_Config_ConfigManager test case.
 */
class Fynd_Config_ConfigManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests Fynd_Config_ConfigManager::getConfig()
     */
    public function testGetConfig ()
    {
        $configXml = include('ConfigXmlForInclude.php');
        try
        {
            $config1 = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig,$configXml);
            $this->assertType('Fynd_Config_DbConfig', $config1);
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
        try {
            Fynd_Config_ConfigManager::getConfig('',$configXml);
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/\$configType can not be null or empty/i',$e->getMessage());
        }
    }
}

