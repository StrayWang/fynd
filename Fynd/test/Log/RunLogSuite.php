<?php
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'LogSuite.php';

PHPUnit_TextUI_TestRunner::run(LogSuite::suite());

?>