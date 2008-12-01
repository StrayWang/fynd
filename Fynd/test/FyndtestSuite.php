<?php
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'test/ApplicationTest.php';
/**
 * Static test suite.
 */
class FyndtestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     */
    public function __construct ()
    {
        $this->setName('FyndtestSuite');
        $this->addTestSuite('Fynd_ApplicationTest');
    }
    /**
     * Creates the suite.
     */
    public static function suite ()
    {
        return new self();
    }
}

