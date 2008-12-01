<?php
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'ConfigSuite.php';

PHPUnit_TextUI_TestRunner::run(ConfigSuite::suite());
?>