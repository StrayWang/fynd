<?php
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'test/Config/ConfigManagerTest.php';
require_once 'test/Config/DbConfigTest.php';
/**
 * Static test suite.
 */
class ConfigSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct ()
    {
        $this->setName('ConfigSuite');
        $this->addTestSuite('Fynd_Config_ConfigManagerTest');
        $this->addTestSuite('Fynd_Config_DbConfigTest');
    }
    /**
     * Creates the suite.
     */
    public static function suite ()
    {
        return new self();
    }
}

