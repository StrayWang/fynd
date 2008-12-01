<?php
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'FyndtestSuite.php';

PHPUnit_TextUI_TestRunner::run(FyndtestSuite::suite());
?>