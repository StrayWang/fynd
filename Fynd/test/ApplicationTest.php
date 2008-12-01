<?php
require_once 'src/Fynd/Application.php';
require_once 'PHPUnit/Framework/TestCase.php';
/**
 * Fynd_Application test case.
 */
class Fynd_ApplicationTest extends PHPUnit_Framework_TestCase
{
    private $_workPath;
    public function __construct ()
    {
        $this->setName('Fynd_ApplicationTest');
        $this->_workPath = realpath('./') . 'TestFile/';
        Fynd_Application::getInstance()->WorkPath = $this->_workPath;
        Fynd_Application::getInstance()->init();
    }
    /**
     * Tests Fynd_Application->getAppWorkPath()
     */
    public function testGetAppWorkPath ()
    {
        $this->assertEquals($this->_workPath, Fynd_Application::getInstance()->getAppWorkPath());
    }
    /**
     * Tests Fynd_Application::getConfigPath()
     */
    public function testGetConfigPath ()
    {
        $this->assertEquals($this->_workPath . 'app/configs/', Fynd_Application::getConfigPath());
    }
    /**
     * Tests Fynd_Application::getCtrlPath()
     */
    public function testGetCtrlPath ()
    {
        $this->assertEquals($this->_workPath . 'app/controllers/', Fynd_Application::getCtrlPath());
    }
    /**
     * Tests Fynd_Application::getInstance()
     */
    public function testGetInstance ()
    {
        $this->assertType('Fynd_Application', Fynd_Application::getInstance());
        $app1 = Fynd_Application::getInstance();
        $app2 = Fynd_Application::getInstance();
        $this->assertEquals($app1, $app2);
    }
    /**
     * Tests Fynd_Application::getLogger()
     */
    public function testGetLogger ()
    {
        try
        {
            $this->assertType('Fynd_Log', Fynd_Application::getLogger($this->getName()));
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
        try
        {
            Fynd_Application::getLogger('');
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/name can not be null or empty/i', $e->getMessage());
        }
    }
    /**
     * Tests Fynd_Application::getModelPath()
     */
    public function testGetModelPath ()
    {
        $this->assertEquals($this->_workPath . 'app/models/', Fynd_Application::getModelPath());
    }
    /**
     * Tests Fynd_Application::getViewPath()
     */
    public function testGetViewPath ()
    {
        $this->assertEquals($this->_workPath . 'app/views/', Fynd_Application::getViewPath());
    }
    /**
     * Tests Fynd_Application->loadClass()
     */
    public function testLoadClass ()
    {
        set_include_path('.' . PATH_SEPARATOR . $this->_workPath . '../' . PATH_SEPARATOR . get_include_path());
        try
        {
            Fynd_Application::getInstance()->loadClass('TestFile_TestClass');
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
        try
        {
            Fynd_Application::getInstance()->loadClass('Test_Class');
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/can not be loaded/i', $e->getMessage());
        }
    }
    /**
     * Tests Fynd_Application->run()
     */
    public function testRun ()
    {
        Fynd_Application::getInstance()->run();
        $this->assertEquals('excuted', $GLOBALS['IndexCtrl::indexAct']);
    }
    /**
     * Tests Fynd_Application::startSession()
     */
    public function testStartSession ()
    {
        Fynd_Application::startSession();
        $this->assertTrue(Fynd_Application::getIsSessionStarted());
    }
    
    public function testInit()
    {
        Fynd_Application::getInstance()->init();
        $this->assertType('Fynd_Log',Fynd_Application::getLogger('root'));
    }
}

