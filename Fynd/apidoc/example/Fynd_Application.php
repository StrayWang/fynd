<?php
require 'Fynd/Application.php';
//Simply,run your application like this:
Fynd_Application::getInstance()->run();

//Get Log like this:
$logger = Fynd_Application::getLogger('root');
$logger->logInfo('I am a logger!'');
?>