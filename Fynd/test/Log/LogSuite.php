<?php
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'Fynd_Log_StreamWriterTest.php';
require_once 'Fynd_LogTest.php';
/**
 * Static test suite.
 */
class LogSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct ()
    {
        $this->setName('LogSuite');
        $this->addTestSuite('Fynd_Log_StreamWriterTest');
        $this->addTestSuite('Fynd_LogTest');
    }
    /**
     * Creates the suite.
     */
    public static function suite ()
    {
        return new self();
    }
}

